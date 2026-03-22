<template>
    <div>
        <Header />
    </div>

    <AddNewEmployee />

    <!-- Employee Table -->
    <v-data-table
        :headers="headers"
        :items="employees"
        class="elevation-1"
    >
    </v-data-table>

</template>

<script>
import AddNewEmployee from './AddNewEmployee.vue';
import Header from '@/components/Header.vue';

export default {
    components: {
        AddNewEmployee,
        Header,
    },
    data() {
        return {
            employees: [],
            headers: [
                { title: 'Name', key: 'name' },
                { title: 'Email', key: 'email' },
                { title: 'Phone', key: 'phone' },
                { title: 'Designation', key: 'designation' },
                { title: 'Monthly Salary', key: 'monthly_salary_package' },
            ],
        };
    },
};
</script>