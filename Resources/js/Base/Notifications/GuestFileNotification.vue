<template>
    <div>
        <p>A file has been uploaded to the File Link {{data.link.name}}</p>
        <div class="text-center mt-4">
            <inertia-link as="b-button" :href="route('FileLinkModule.show', data.link.link_id)" variant="info" @click="markMessage">Click to View the File</inertia-link>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            data: {
                type:     Object,
                required: true,
            }
        },
        methods: {
            markMessage()
            {
                this.$emit('read');
            },
        },
    }
</script>
