<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Edit File Link</h4>
            </div>
        </div>
        <div class="row justify-content-center grid-margin">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <b-overlay :show="submitted">
                            <template #overlay>
                                <form-loader></form-loader>
                            </template>
                            <ValidationObserver v-slot="{handleSubmit}">
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.link_name" label="Link Name" name="name" placeholder="Enter a user friendly name for this link" rules="required"></text-input>
                                    <date-picker v-model="form.expire" label="Expires Date" name="expires" placeholder="Select the date the link expires" rules="required"></date-picker>
                                    <b-form-checkbox v-model="form.allow_upload" switch>
                                        Allow Visitor to Upload Files
                                    </b-form-checkbox>
                                    <div class="my-2 text-center">
                                        <b-button switch variant="info" @click="form.has_instructions = !form.has_instructions">{{instructionText}}</b-button>
                                    </div>
                                    <b-collapse id="instructions" :visible="form.has_instructions">
                                        <text-editor v-model="form.instructions" placeholder="Enter Instructions" label="Instructions"></text-editor>
                                    </b-collapse>
                                    <submit-button button_text="Update File Link" :submitted="submitted"></submit-button>
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
            details: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    link_name:        this.details.link_name,
                    expire:           this.details.expire,
                    allow_upload:     this.details.allow_upload,
                    has_instructions: this.details.instructions == null ? false : true,
                    instructions:     this.details.instructions,
                }),
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
            instructionText()
            {
                return this.form.has_instructions ? 'Remove Instructions' : 'Add Instructions';
            }
        },
        watch: {
            //
        },
        methods: {
            //
            submitForm()
            {
                this.submitted = true;
                if(!this.form.has_instructions)
                {
                    this.form.instructions = null;
                }

                // this.$inertia.put(route('FileLinkModule.update', this.details.link_id), this.form);

                console.log(this.form);

                this.form.put(route('FileLinkModule.update', this.details.link_id));
            },
        },
    }
</script>
