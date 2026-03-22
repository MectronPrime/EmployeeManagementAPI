import EmployeeManagement from "./module/EmployeeManagement";
import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/employee-management' },
        ...EmployeeManagement,
    ],
});

export default router;