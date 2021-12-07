<template>
    <div>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <h1 class="text-center">{{$page.props.app.name}}</h1>
            </div>
        </div>
        <div class="row justify-content-center" v-if="details.instructions">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body" v-html="details.instructions"></div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4" v-if="files.length > 0">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">You have files available to download</div>
                    </div>
                    <div class="m-4">
                        <b-table :items="files" :fields="fields" responsive striped>
                            <template #cell(file_uploads)="data">
                                <a :href="route('download', [data.item.file_uploads.file_id, data.item.file_uploads.file_name])">{{data.item.file_uploads.file_name}}</a>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4" v-if="details.allow_upload">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Upload A File</div>
                    </div>
                    <div class="p-4">
                        <b-overlay :show="submitted">
                            <template #overlay>
                                <progress-bar :percent-done="fileProgress" text="Creating File Link"></progress-bar>
                            </template>
                            <ValidationObserver v-slot="{handleSubmit}">
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.name" label="Your Name" name="name" placeholder="Please Enter Your Name" rules="required"></text-input>
                                    <dropzone-upload
                                        ref="dropzone-upload"
                                        :max-files="5"
                                        disk="fileLinks"
                                        @upload-canceled="canceled"
                                        @upload-progress="updateProgressbar"
                                        @completed="uploadDone"
                                        @validation-error="canceled"
                                    />
                                    <text-editor v-model="form.notes" placeholder="Tell me about this file" label="Additional Information"></text-editor>
                                    <submit-button button_text="Upload File" :submitted="submitted"></submit-button>
                                </b-form>
                            </ValidationObserver>
                        </b-overlay>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            details: {
                type:     Object,
                required: true,
            },
            files: {
                type:     Array,
                required: false,
            }
        },
        data() {
            return {
                fields: [
                    {
                        key:     'created_at',
                        label:   'Uploaded Date',
                        sortable: true,
                    },
                    {
                        key:     'file_uploads',
                        label:   'File',
                        sortable: true,
                    }
                ],
                submitted: false,
                fileProgress: 0,
                form: this.$inertia.form({
                    name:      null,
                    notes:     null,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.$refs['dropzone-upload'].process();
            },
            //  If a file was canceled during upload, go back to form
            canceled()
            {
                this.submitted = false;
            },
            //  Update the overlay's progress bar
            updateProgressbar(progress)
            {
                this.fileProgress = progress;
            },
            //  File upload is completed
            uploadDone()
            {
                this.form.put(route('FileLinkModule.guest.update', this.details.link_hash), {
                    onFinish: ()=> {
                        this.$refs['dropzone-upload'].resetDropzone();
                        this.submitted = false;
                        this.fileProgress = 0;
                        this.$bvModal.msgBoxOk('Upload Complete.  You can upload more if necessary');
                    }
                });
            }
        },
    }
</script>
