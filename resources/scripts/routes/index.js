import { createWebHistory, createRouter } from "vue-router";
import Create from "../pages/Create.vue";
import List from "../pages/List.vue";
import View from "../pages/View.vue";


const routes = [
    {
        path: '/',
        component: List,
        name: 'list',
    },
    {
        path: '/email',
        component: Create,
        name: 'email',
    },
    {
        path: '/email/:id',
        component: View,
        name: 'viewEmail',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
