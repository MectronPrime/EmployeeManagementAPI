import EmployeeManagment from "./module/EmployeeManagement";

import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes: [...EmployeeManagment],
});