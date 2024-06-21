<template>
    <div>
        <DocumentImportForm class="p-2" />
        <DocumentImportList class="p-2" />
    </div>
</template>

<script>

import DocumentImportList from './DocumentImportList.vue';
import DocumentImportForm from './DocumentImportForm.vue';

export default {
    name: "DocumentImport",
    components: {
        DocumentImportForm,
        DocumentImportList,
    },
}
</script>

<style scoped>

</style>
