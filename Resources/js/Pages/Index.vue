<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4>File Upload Links</h4>
            </div>
        </div>
        <div class="row justify-content-center" v-if="!is_admin">
            <div class="col-8">
                <div class="card">
                    <div class="card-body text-center">
                        <inertia-link
                            :href="route('FileLinkModule.create')"
                            as="b-button"
                            variant="primary"
                            pill
                        >
                            <i class="fas fa-plus" />
                            Create New File Link
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title" v-if="is_admin">
                            {{user}}
                        </div>
                        <b-overlay :show="loading">
                            <template #overlay>
                                <atom-loader />
                            </template>
                            <div v-if="link_list.length == 0">
                                <h5 class="text-center">No File Links</h5>
                            </div>
                            <b-table
                                v-else
                                :fields="fields"
                                :items="link_list"
                                :tbody-tr-class="getRowClass"
                                empty-text="No File Links"
                                striped
                                responsive
                                show-empty
                            >
                                <template #cell(link_name)="data">
                                    <inertia-link
                                        :href="route('FileLinkModule.show', data.item.link_id)"
                                    >
                                        {{data.item.link_name}}
                                    </inertia-link>
                                </template>
                                <template #cell(actions)="data">
                                    <inertia-link
                                        v-if="data.item.length > 0 && !data.item.is_expired"
                                        :href="route('FileLinkModule.disable', data.item.link_id)"
                                        title="Disable Link"
                                        class="pointer text-muted"
                                        v-b-tooltip.hover
                                        @click="loading = true"
                                    >
                                        <i class="fas fa-unlink pointer" />
                                    </inertia-link>
                                    <i
                                        class="fas fa-trash-alt pointer text-danger"
                                        title="Delete Link"
                                        v-b-tooltip.hover
                                        @click="deleteLink(data.item.link_id)"
                                    />
                                </template>
                            </b-table>
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
            link_list: {
                type:     Array,
                required: true,
            },
            is_admin: {
                type:     Boolean,
                required: false,
                default:  false,
            },
            user: {
                type:     String,
                required: false,
            }
        },
        data() {
            return {
                loading: false,
                fields: [
                    {
                        key:     'link_name',
                        label:   'Link Name',
                        sortable: true,
                    },
                    {
                        key:     'expire_formatted',
                        label:   'Expires',
                        sortable: true,
                    },
                    {
                        key:     'file_count',
                        label:   'Files',
                        sortable: true,
                    },
                    {
                        key:     'actions',
                        label:   'Actions',
                        sortable: false,
                    },
                ]
            }
        },
        methods: {
            getRowClass(row)
            {
                if(row)
                {
                    return row.is_expired ? 'table-danger' : '';
                }

                return '';
            },
            deleteLink(link_id)
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
                        this.$inertia.delete(this.route('FileLinkModule.destroy', link_id), {
                            onFinish: () => {
                                this.loading = false;
                            }
                        });
                    }
                });
            }
        },
    }
</script>
