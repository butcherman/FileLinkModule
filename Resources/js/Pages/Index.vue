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
                        <inertia-link :href="route('FileLinkModule.create')" as="b-button" pill variant="primary" size="lg"><i class="fas fa-plus"></i> Create New File Link</inertia-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <b-overlay :show="loading">
                            <template #overlay>
                                <atom-loader></atom-loader>
                            </template>
                            <div v-if="link_list.length == 0">
                                <h5 class="text-center">No File Links</h5>
                            </div>
                            <b-table v-else striped :items="link_list" :fields="fields" empty-text="No File Links" responsive show-empty :tbody-tr-class="getRowClass">
                                <template #cell(link_name)="data">
                                    <inertia-link :href="route('FileLinkModule.show', data.item.link_id)">{{data.item.link_name}}</inertia-link>
                                </template>
                                <template #cell(actions)="data">
                                    <inertia-link v-if="data.item.length > 0 && !data.item.is_expired" :href="route('FileLinkModule.disable', data.item.link_id)" @click="loading = true" class="pointer text-muted" title="Disable Link" v-b-tooltip.hover>
                                        <i class="fas fa-unlink pointer"></i>
                                    </inertia-link>
                                    <i class="fas fa-trash-alt pointer text-muted" title="Delete Link" v-b-tooltip.hover @click="deleteLink(data.item.link_id)"></i>
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
