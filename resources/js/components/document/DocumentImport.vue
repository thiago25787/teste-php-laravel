<template>
    <div>
        <DocumentImportForm class="p-2" @notifyUpdate="notifyUpdate" />
        <DocumentImportList class="p-2" :shouldUpdate="shouldUpdate" />
    </div>
</template>

<script>

import DocumentImportList from './DocumentImportList.vue';
import DocumentImportForm from './DocumentImportForm.vue';

export default {
    name: "DocumentImport",
    data() {
        return {
            shouldUpdate: false
        };
    },
    methods: {
        notifyUpdate() {
            this.shouldUpdate = !this.shouldUpdate;
        }
    },
    components: {
        DocumentImportForm,
        DocumentImportList,
    },
}
</script>

<style scoped>

</style>
