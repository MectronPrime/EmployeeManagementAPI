<template>
    <div>
        <h2>Employee Management</h2>
        <p>Welcome to the Employee Management System.</p>
    </div>
    
    <!-- Add New Employee button -->
    <v-row class="mb-4">
        <v-col cols="12" class="text-right">
            <v-btn color="primary" @click="$router.push('/employee-management/add')">Add New Employee</v-btn>
        </v-col>
    </v-row>

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

</script>