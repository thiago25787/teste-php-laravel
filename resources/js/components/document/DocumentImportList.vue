<template>
    <div>
        <div class="card">
            <div class="card-header">
                Documentos já processados
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="col-sm-4">T&iacute;tulo</th>
                            <th class="col-sm-2">Exerc&iacute;cio</th>
                            <th class="col-sm-3">Categoria</th>
                            <th class="col-sm-3">Data de processamento</th>
                        </tr>
                    </thead>
                    <tbody v-if="documents.length > 0">
                        <tr v-for="document in documents">
                            <td>{{ document.title }}</td>
                            <td>{{ document.financial_year }}</td>
                            <td>{{ document.category.name }}</td>
                            <td>{{ document.date }}</td>
                        </tr>
                    </tbody>
                    <tfoot v-else>
                        <tr>
                            <td colspan="4" class="text-center">Nenhum documento encontrado</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
                <nav v-if="meta" class="text-center">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link cursor-pointer" v-if="meta.current_page > 1" @click="listDocuments((meta.current_page - 1))">Anterior</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link cursor-pointer" v-if="meta.last_page > meta.current_page" @click="listDocuments((meta.current_page + 1))">Pr&oacute;xima</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: "DocumentImportList",
    props: ['shouldUpdate'],
    data() {
        return {
            documents: [],
            meta: [],
        };
    },
    watch: {
        shouldUpdate() {
            this.listDocuments(1);
        }
    },
    methods: {
        async listDocuments(page) {
            this.documents = [];
            this.meta = [];

            await axios.get('/api/v1/document?page=' + page)
                .then(response => {
                    this.documents = response.data.data;
                    this.meta = response.data.meta;
                }).catch(error => {
                    this.handleErrors(error);
                });
        },
        async processDocuments() {
            await axios.get('/api/v1/document/process-queue')
                .then(response => {
                    Swal.fire({
                        title: "Sucesso!",
                        text: response.data.message,
                        icon: "success",
                    });
                    setTimeout(() => {
                        this.listDocuments(1);
                    }, 2000);
                }).catch(error => {
                    this.handleErrors(error);
                });
        },
        handleErrors(error) {
            let errors = [];
            if (error.response?.data?.errors) {
                for (let key in error.response.data.errors) {
                    error.response.data.errors[key].forEach((message) => {
                        errors.push(message);
                    });
                }
            } else if (error.response?.data?.message) {
                errors.push(error.response.data.message);
            } else if (error?.message) {
                errors.push(error.message);
            }

            if (errors.length > 0) {
                Swal.fire({
                    title: "Erro!",
                    html: errors.join('<br/>'),
                    icon: "error",
                });
            }
        },
    },
    created() {
        this.listDocuments(1);
    },
}
</script>

<style scoped>
    .cursor-pointer {
        cursor: pointer;
    }
    .card-body {
        min-height: 550px;
    }
</style>
