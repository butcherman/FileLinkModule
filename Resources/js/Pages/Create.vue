<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4>Create File Link</h4>
            </div>
        </div>
        <div class="row justify-content-center grid-margin">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <b-overlay :show="submitted">
                            <template #overlay>
                                <progress-bar v-if="uploading" :percent-done="fileProgress" />
                                <form-loader v-else />
                            </template>
                            <ValidationObserver v-slot="{handleSubmit}">
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.link_name" label="Link Name" name="name" placeholder="Enter a user friendly name for this link" rules="required"></text-input>
                                    <date-picker v-model="form.expire" label="Expires Date" name="expires" placeholder="Select the date the link expires" rules="required"></date-picker>
                                    <b-form-checkbox v-model="form.allow_upload" switch>
                                        Allow Visitor to Upload Files
                                    </b-form-checkbox>
                                    <div class="my-2 text-center">
                                        <b-button pill @click="form.has_instructions = !form.has_instructions" variant="info">{{instructionText}}</b-button>
                                    </div>
                                    <b-collapse id="instructions" :visible="form.has_instructions">
                                        <text-editor v-model="form.instructions" placeholder="Enter Instructions" label="Instructions"></text-editor>
                                    </b-collapse>
                                    <dropzone-upload
                                        ref="dropzone-upload"
                                        :max-files="5"
                                        disk="fileLinks"
                                        @upload-canceled="canceled"
                                        @upload-progress="updateProgressbar"
                                        @completed="createFileLink"
                                        @validation-error="canceled"
                                    />
                                    <submit-button button_text="Create File Link" :submitted="submitted"></submit-button>
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
    import App from '../../../../../resources/js/Layouts/app';

    export default {
        layout: App,
        props: {
            expire: {
                type:     String,
                required: true,
            }
        },
        data() {
            return {
                submitted:    false,
                uploading:    false,
                fileProgress: 0,
                form: this.$inertia.form({
                    link_name:        null,
                    expire:           this.expire,
                    allow_upload:     true,
                    has_instructions: false,
                    instructions:     null,
                }),
            }
        },
        computed: {
            instructionText()
            {
                return this.form.has_instructions ? 'Remove Instructions' : 'Add Instructions';
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                if(this.$refs['dropzone-upload'].getFileCount() > 0)
                {
                    this.uploading = true;
                    this.$refs['dropzone-upload'].process();
                }
                else
                {
                    this.createFileLink();
                }
            },
            createFileLink()
            {
                this.uploading = false;
                this.form.post(route('FileLinkModule.store'));
            },
            //  If a file was canceled during upload, go back to form
            canceled()
            {
                this.submitted = false;
                this.loading   = false;
            },
            //  Update the overlay's progress bar
            updateProgressbar(progress)
            {
                this.fileProgress = progress;
            },
        },
    }
</script>
