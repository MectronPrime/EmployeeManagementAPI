<template>
    <div>
        <h2>Welcome to the Employee Management System.</h2>
    </div>
    
    <AddNewEmployee />

    <!-- Employee Delete Button -->
    <v-row class="mb-4">
        <v-col cols="12" class="text-right">
            <v-btn color="error" @click="$router.push('/employee-management/delete')">Delete Employee</v-btn>
        </v-col>
    </v-row>


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

</script>