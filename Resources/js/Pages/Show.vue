<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">
                    File Link Details
                </h4>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-md-9 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Details:</div>
                        <b-table stacked small :items="linkDetails">
                            <template #cell(AllowUpload)="data">
                                <i
                                    class="fas"
                                    :class="data.item.AllowUpload ?
                                        'fa-check-circle text-success' :
                                        'fas fa-times-circle text-danger'"
                                />
                            </template>
                            <template #cell(HasInstructions)="data">
                                <i
                                    class="fas"
                                    :class="data.item.HasInstructions ?
                                        'fa-check-circle text-success' :
                                        'fas fa-times-circle text-danger'"
                                />
                            </template>
                            <template #cell(LinkURL)="data">
                                <a
                                    :href="data.item.LinkURL"
                                    target="_blank"
                                >
                                    {{data.item.LinkURL}}
                                </a>
                                <b-button
                                    pill
                                    :variant="copyClass"
                                    size="sm"
                                    class="d-block d-md-inline-block"
                                    title="Copy Link to Clipboard"
                                    v-clipboard:copy="data.item.LinkURL"
                                    v-clipboard:success="onCopySuccess"
                                    v-clipboard:error="onCopyError"
                                    v-b-tooltip.hover>
                                    <i class="fas fa-copy" />
                                </b-button>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <b-button
                            block
                            pill
                            variant="info"
                            :href="`mailto:?subject=A File Link Has Been Created
                                    For You&body=View the link details here:
                                    ${route('FileLinkModule.guest', this.details.link_hash)}`"
                        >
                            <i class="far fa-envelope" />
                            Email Link
                        </b-button>
                        <inertia-link
                            as="b-button"
                            variant="warning"
                            :href="route('FileLinkModule.edit', details.link_id)"
                            pill
                            block
                        >
                            <i class="fas fa-pencil-alt" />
                            Edit
                        </inertia-link>
                        <b-button
                            variant="danger"
                            pill
                            block
                            @click="deleteLink"
                        >
                            <i class="far fa-trash-alt" />
                            Delete
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title clearfix">
                            Files:
                            <b-button
                                size="sm"
                                class="float-right"
                                :class="{'fa-spin' : loading}"
                                pill
                                title="Refresh File List"
                                v-b-tooltip.hover
                                @click="reload"
                            >
                                <i class="fas fa-sync" />
                            </b-button>
                        </div>
                        <b-table
                            :busy="loading"
                            :fields="fileFields"
                            :items="details.file_link_file"
                            empty-text="No Files"
                            striped
                            show-empty
                            responsive
                        >
                            <template #table-busy>
                                <atom-loader />
                            </template>
                            <template #cell(file_name)="data">
                                <a
                                    :href="route('download', [
                                        data.item.file_uploads.file_id,
                                        data.item.file_uploads.file_name
                                    ])"
                                >
                                    {{data.item.file_uploads.file_name}}
                                </a>
                            </template>
                            <template #cell(added_by)="data">
                                <span v-if="data.item.user_id">
                                        {{data.item.user.full_name}}
                                    </span>
                                <span v-else>{{data.item.added_by}}</span>
                            </template>
                            <template #cell(notes)="data">
                                <div v-if="data.item.note">
                                    <i
                                        title="Click to open note"
                                        class="fas fa-comment-dots pointer text-danger"
                                        v-b-tooltip.hover
                                        @click="openNote(data.item.note)"
                                    />
                                </div>
                            </template>
                            <template #cell(actions)="data">
                                <i
                                    class="fas fa-trash-alt pointer text-danger"
                                    title="Delete File"
                                    v-b-tooltip.hover
                                    @click="deleteFile(data)"
                                />
                            </template>
                        </b-table>
                        <div class="text-center">
                            <b-button
                                variant="info"
                                pill
                                small
                                v-b-modal.add-file-modal
                            >
                                <i class="fas fa-plus" />
                                Add File
                            </b-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal
            id="add-file-modal"
            title="Add File"
            ref="add-file-modal"
            @ok.prevent="addFile"
        >
            <b-overlay :show="loading">
                <template #overlay>
                    <progress-bar
                        :percent-done="fileProgress"
                        text="Uploading File..."
                    />
                </template>
                <dropzone-upload
                    ref="dropzone-upload"
                    disk="fileLinks"
                    :max-files="5"
                    :folder="details.link_id"
                    :public="true"
                    @upload-progress="updateProgressbar"
                    @completed="uploadDone"
                    @upload-canceled="canceled"
                    @validation-error="canceled"
                ></dropzone-upload>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    import App          from '../../../../../resources/js/Layouts/app';
    import Vue          from 'vue';
    import VueClipboard from 'vue-clipboard2';

    Vue.use(VueClipboard);

    export default {
        layout: App,
        props: {
            details: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                fileProgress: 0,
                loading     : false,
                copyClass   :  'outline-secondary',
                linkDetails : [
                    {
                        LinkName       : this.details.link_name,
                        ExpiresOn      : this.details.expire_formatted,
                        AllowUpload    : this.details.allow_upload,
                        HasInstructions: this.details.instructions == null ? false : true,
                        LinkURL        : route('FileLinkModule.guest', this.details.link_hash),
                    }
                    ],
                fileFields: [
                    {
                        key:     'file_name',
                        label:   'File Name',
                        sortable: true,
                    },
                    {
                        key:     'created_at',
                        label:   'Date Added',
                        sortable: true,
                    },
                    {
                        key:     'added_by',
                        label:   'Added By',
                        sortable: true,
                    },
                    {
                        key:     'notes',
                        label:   'File Notes',
                        sortable: true,
                    },
                    {
                        key:     'actions',
                        label:   '',
                        sortable: false,
                    },
                ],
            }
        },
        methods: {
            deleteLink()
            {
                this.$bvModal.msgBoxConfirm('All associated files will also be deleted', {
                    title:          'This action canot be undone',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                }).then(value => {
                    if(value)
                    {
                        this.loading = true;
                        this.$inertia.delete(route('FileLinkModule.destroy', this.details.link_id));
                    }
                });
            },
            openNote(note)
            {
                this.$bvModal.msgBoxOk(note, {
                    title:      'File Notes',
                    size:       'lg',
                    buttonSize: 'sm',
                    centered:    true
                });
            },
            deleteFile(file)
            {
                this.$bvModal.msgBoxConfirm('Are you sure?', {
                    title:          'This action canot be undone',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                }).then(value => {
                    if(value)
                    {
                        this.loading = true;
                        this.$inertia.delete(route('FileLinkModule.files.destroy', file.item.link_file_id), {
                            onFinish: ()=> {
                                this.loading = false;
                            }
                        });
                    }
                });
            },
            addFile()
            {
                if(this.$refs['dropzone-upload'].getFileCount() > 0)
                {
                    this.loading = true;
                    this.$refs['dropzone-upload'].process();
                }
                else
                {
                    this.$refs['add-file-modal'].hide();
                }
            },
            //  Update the overlay's progress bar
            updateProgressbar(progress)
            {
                this.fileProgress = progress;
            },
            //  File upload is completed
            uploadDone()
            {
                this.$inertia.put(route('FileLinkModule.files.update', this.details.link_id), {}, {
                    only     : ['details', 'flash', 'errors'],
                    onError  : (error) => this.eventHub.$emit('validationError', error),
                    onSuccess: () => this.$refs['add-file-modal'].hide(),
                    onFinish : () => this.loading = false
                });
            },
            //  Do a partial reload on the page to get new files
            reload()
            {
                this.loading = true;
                this.$inertia.reload({
                    only    : ['details', 'errors'],
                    onFinish: () => this.loading = false,
                });
            },
             //  If a file was canceled during upload, go back to form
            canceled()
            {
                this.loading   = false;
            },
            //  Successful and Error functions for copy link URL to clipboard
            onCopySuccess()
            {
                this.copyClass = 'success';
            },
            onCopyError()
            {
                this.copyClass = 'danger';
            },
        },
    }
</script>
