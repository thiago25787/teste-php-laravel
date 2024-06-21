import { createRouter, createWebHistory } from "vue-router";
import DocumentImport from "./components/document/DocumentImport.vue";

const routes = [
    {
        path: "/",
        redirect: "/document/import",
    },
    {
        path: "/document/import",
        component: DocumentImport,
    },
];

const router = createRouter({
    history: createWebHistory("/"),
    routes,
});

export default router;
