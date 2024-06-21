<template>
    <div>
        <form id="importForm" method="POST" enctype="multipart/form-data" class="needs-validation">
            <div class="card">
                <div class="card-header">
                    Importa&ccedil;&atilde;o de documentos
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="file">Arquivo para importa&ccedil;&atilde;o (JSON):</label>
                        <div class="col-sm-10 has-validation">
                            <input type="file" @change="selectedFile" accept=".json" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" @click="importFile()" class="btn btn-primary">Importar</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: "DocumentImport",
    data() {
        return {
            file: null,
        };
    },
    methods: {
        selectedFile(event) {
            this.file = event.target.files[0];
        },
        async importFile() {
            let formData = new FormData();
            formData.append('file', this.file);
            const headers = {
                'Content-Type': 'multipart/form-data'
            };

            await axios.post('/api/v1/document', formData, {headers})
                .then(response => {
                    Swal.fire({
                        title: "Sucesso!",
                        text: response.data.message,
                        icon: "success",
                    });
                }).catch(error => {
                    if (error.response?.data?.errors) {
                        let errors = [];
                        for (let key in error.response.data.errors) {
                            error.response.data.errors[key].forEach((message) => {
                                errors.push(message);
                            });
                        }

                        Swal.fire({
                            title: "Erro!",
                            html: errors.join('<br/>'),
                            icon: "error",
                        });
                    }
                }).finally(() => {
                    this.file = null;
                    document.getElementById('importForm').reset();
                });
        },
    },
}
</script>

<style scoped>

</style>
