import { createRouter, createWebHistory } from "vue-router";
import DashboardPageVue from "./../../components/Views/Pages/DashboardPage.vue";
import TeamsIndexVue from "./../../components/Views/Pages/Teams/TeamsIndex.vue";

// * routes
const routes = [
    { path: "/", component: () => DashboardPageVue },
    { path: "/teams", component: () => TeamsIndexVue },
];

export default createRouter({
    history: createWebHistory(),
    routes: routes,
});
