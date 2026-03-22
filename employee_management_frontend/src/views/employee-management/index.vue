<template>
    <div>
        <Header />
    </div>

    <AddNewEmployee @created="fetchEmployees" />

    <!-- Employee Table -->
    <v-container class="mt-4">
        <v-card elevation="2" rounded="lg">
            <v-card-title class="table-heading d-flex align-center ga-2 pa-4">
                <v-icon icon="mdi-account-group" size="22" />
                Employee List
                <v-chip size="small" color="white" variant="outlined" class="ml-2">
                    {{ employees.length }} records
                </v-chip>

                <v-spacer />

                <v-btn
                    icon="mdi-refresh"
                    variant="text"
                    color="white"
                    size="small"
                    :loading="loading"
                    @click="fetchEmployees"
                    title="Refresh"
                />
            </v-card-title>

            <v-data-table
                :headers="headers"
                :items="employees"
                :loading="loading"
                loading-text="Loading employees..."
                no-data-text="No employees found."
                hover
                class="employee-table"
            >
                <!-- Custom Actions column slot -->
                <template #item.actions="{ item }">
                    <v-btn
                        size="small"
                        color="primary"
                        variant="tonal"
                        prepend-icon="mdi-pencil"
                        @click="openEditDialog(item)"
                    >
                        Edit
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
    </v-container>

    <!-- Edit Employee Dialog -->
    <EditEmployee
        v-if="selectedEmployee"
        v-model="editDialog"
        :employee="selectedEmployee"
        @saved="fetchEmployees"
    />
</template>

<script>
import AddNewEmployee from './AddNewEmployee.vue';
import EditEmployee from './EditEmployee.vue';
import Header from '@/components/Header.vue';
import EmployeeApi from '@/Services/Modules/EmployeeApi';

export default {
    components: {
        AddNewEmployee,
        EditEmployee,
        Header,
    },

    data() {
        return {
            employees: [],
            loading: false,
            editDialog: false,
            selectedEmployee: null,
            headers: [
                { title: 'Name',           key: 'name',                   sortable: true  },
                { title: 'Email',          key: 'email',                  sortable: true  },
                { title: 'Phone',          key: 'phone',                  sortable: false },
                { title: 'Designation',    key: 'designation',            sortable: true  },
                { title: 'Monthly Salary', key: 'monthly_salary_package', sortable: true  },
                { title: 'Actions',        key: 'actions',                sortable: false },
            ],
        };
    },

    mounted() {
        this.fetchEmployees();
    },

    methods: {
        async fetchEmployees() {
            this.loading = true;
            try {
                const response = await EmployeeApi.getAllEmployeeDetails();
                this.employees = response.data?.data ?? response.data ?? [];
            } catch (err) {
                console.error('Failed to load employees:', err);
                this.employees = [];
            } finally {
                this.loading = false;
            }
        },

        openEditDialog(employee) {
            this.selectedEmployee = { ...employee };
            this.editDialog = true;
        },
    },
};
</script>

<style scoped>
.table-heading {
    background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
    color: white;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 8px 8px 0 0;
}

.employee-table :deep(thead tr th) {
    background-color: #f5f7fa !important;
    font-weight: 700;
    color: #37474f;
    font-size: 0.82rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.employee-table :deep(tbody tr:hover td) {
    background-color: #e3f2fd !important;
    transition: background-color 0.2s ease;
}
</style>