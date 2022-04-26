<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">File Link Details</h4>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-md-9 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Details:</div>
                        <b-table stacked small :items="table.items">
                            <template #cell(AllowUpload)="data">
                                <i v-if="data.item.AllowUpload" class="fas fa-check-circle text-success"></i>
                                <i v-else class="fas fa-times-circle text-danger"></i>
                            </template>
                            <template #cell(HasInstructions)="data">
                                <i v-if="data.item.HasInstructions" class="fas fa-check-circle text-success"></i>
                                <i v-else class="fas fa-times-circle text-danger"></i>
                            </template>
                            <template #cell(LinkURL)="data">
                                <a :href="data.item.LinkURL" target="_blank">{{data.item.LinkURL}}</a>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
            <div class="col-md-3 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <b-button block pill variant="info" :href="'mailto:?subject=A File Link Has Been Created For You&body=View the link details here: '+route('FileLinkModule.guest', this.details.link_hash)">Email Link</b-button>
                        <inertia-link as="b-button" :href="route('FileLinkModule.edit', details.link_id)" block pill variant="warning">Edit</inertia-link>
                        <b-button block pill variant="danger" @click="deleteLink">Delete Link</b-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Files:
                        </div>
                        <b-table :items="details.file_link_file" :fields="files.fields" striped responsive :busy="loading" empty-text="No Files" show-empty>
                            <template #table-busy>
                                <atom-loader></atom-loader>
                            </template>
                            <template #cell(file_name)="data">
                                <a :href="route('download', [data.item.file_uploads.file_id, data.item.file_uploads.file_name])">{{data.item.file_uploads.file_name}}</a>
                            </template>
                            <template #cell(added_by)="data">
                                <span v-if="data.item.user_id">{{data.item.user.full_name}}</span>
                                <span v-else>{{data.item.added_by}}</span>
                            </template>
                            <template #cell(notes)="data">
                                <div v-if="data.item.note">
                                    <i class="fas fa-comment-dots pointer text-danger" title="Click to open note" v-b-tooltip.hover @click="openNote(data.item.note)"></i>
                                </div>
                            </template>
                            <template #cell(actions)="data">
                                <i class="fas fa-trash-alt pointer text-danger" title="Delete File" v-b-tooltip.hover @click="deleteFile(data)"></i>
                            </template>
                        </b-table>
                        <div class="text-center">
                            <b-button pill small variant="info" v-b-modal.add-file-modal>Add File</b-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal title="Add File" id="add-file-modal" ref="add-file-modal" @ok.prevent="addFile">
            <b-overlay :show="loading">
                <template #overlay>
                    <progress-bar :percent-done="fileProgress" text="Uploading File..."></progress-bar>
                </template>
                <dropzone-upload
                    ref="dropzone-upload"
                    :max-files="5"
                    :folder="details.link_id"
                    :public="true"
                    disk="fileLinks"
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
    import App from '../../../../../resources/js/Layouts/app';

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
                loading: false,
                table: {
                    items: [
                        {
                            LinkName:        this.details.link_name,
                            ExpiresOn:       this.details.expire_formatted,
                            AllowUpload:     this.details.allow_upload,
                            HasInstructions: this.details.instructions == null ? false : true,
                            LinkURL:         route('FileLinkModule.guest', this.details.link_hash),
                        }
                    ],
                },
                files: {
                    fields: [
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
                },
            }
        },
        created() {
            //
        },
        mounted() {
            //
        },
        computed: {
            //
        },
        watch: {
            //
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
                        this.$inertia.delete(this.route('FileLinkModule.destroy', this.details.link_id));
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
                        // axios.delete(this.route('FileLinkModule.file.delete', file.item.link_file_id))
                        //     .then(res => {
                        //         console.log(res);
                        //         this.files.items.splice(file.index, 1);
                        //         this.loading = false;
                        //     }).catch(error => this.eventHub.$emit('axiosError', error));

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
                    onFinish: () => {
                        this.$refs['add-file-modal'].hide();
                        this.loading = false;
                    }
                });
            },
             //  If a file was canceled during upload, go back to form
            canceled()
            {
                this.loading   = false;
            },
        },
    }
</script>
