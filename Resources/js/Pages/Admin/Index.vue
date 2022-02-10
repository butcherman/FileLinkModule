<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">File Links Administration</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <vue-good-table
                            :columns="columns"
                            :rows="file_links"
                            styleClass="vgt-table striped bordered"
                            @on-row-click="viewLinks"
                        ></vue-good-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../../../../../resources/js/Layouts/app';

    export default {
        layout: App,
        props: {
            file_links: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                columns: [
                    {
                        label: 'User',
                        field: 'full_name',
                        filterOptions: {
                            enabled: true,
                        }
                    },
                    {
                        label: 'Total Links',
                        field: 'FileLinkCount',
                    },
                    {
                        label: 'Expired Links',
                        field: 'ExpiredLinkCount',
                    }
                ],
            }
        },
        methods: {
            viewLinks(data)
            {
                this.$inertia.get(route('FileLinkModule.admin.show', data.row.username));
            }
        },
    }
</script>
