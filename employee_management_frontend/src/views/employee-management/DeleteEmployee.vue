<template>
    <v-dialog v-model="dialog" max-width="450" persistent>
        <v-card rounded="lg" elevation="10" class="delete-dialog">
            <!-- Dialog Header -->
            <v-card-title class="delete-header d-flex align-center ga-2 pa-5">
                <v-icon icon="mdi-account-remove" size="26" color="white" />
                <span class="text-white font-weight-bold">Delete Employee</span>
                <v-spacer />
                <v-btn icon="mdi-close" variant="text" color="white" size="small" @click="closeDialog" :disabled="isDeleting" />
            </v-card-title>

            <v-card-text class="pa-6 text-center">
                <v-avatar color="red-lighten-5" size="70" class="mb-4">
                    <v-icon icon="mdi-alert-circle" color="red" size="40" />
                </v-avatar>

                <h3 class="text-h6 font-weight-bold mb-2">Are you sure?</h3>
                <p class="text-body-1 text-grey-darken-1 mb-4">
                    This action will permanently delete <br />
                    <span class="text-primary font-weight-bold">{{ employee.name }}</span>
                    from the system.
                </p>

                <!-- Alerts -->
                <v-alert v-if="successMessage" type="success" variant="tonal" class="mb-2" closable>
                    {{ successMessage }}
                </v-alert>
                <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-2" closable>
                    {{ errorMessage }}
                </v-alert>
            </v-card-text>

            <v-divider />

            <!-- Dialog Actions -->
            <v-card-actions class="pa-4 ga-3">
                <v-btn
                    variant="outlined"
                    color="grey-darken-1"
                    block
                    class="flex-1-1"
                    :disabled="isDeleting"
                    @click="closeDialog"
                >
                    No, Keep it
                </v-btn>
                <v-btn
                    color="red-darken-1"
                    variant="elevated"
                    block
                    class="flex-1-1"
                    :loading="isDeleting"
                    prepend-icon="mdi-trash-can"
                    @click="confirmDelete"
                >
                    Yes, Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import EmployeeApi from '@/Services/Modules/EmployeeApi';

export default {
    name: 'DeleteEmployee',

    props: {
        // The employee object selected for deletion
        employee: {
            type: Object,
            required: true,
            default: () => ({ id: null, name: '' }),
        },
        modelValue: {
            type: Boolean,
            default: false,
        },
    },

    emits: ['update:modelValue', 'deleted'],

    data() {
        return {
            isDeleting: false,
            successMessage: '',
            errorMessage: '',
        };
    },

    computed: {
        dialog: {
            get() { return this.modelValue; },
            set(val) { this.$emit('update:modelValue', val); },
        },
    },

    watch: {
        // Clear alerts when dialog opens
        modelValue(val) {
            if (val) {
                this.successMessage = '';
                this.errorMessage = '';
            }
        },
    },

    methods: {
        closeDialog() {
            this.dialog = false;
        },

        async confirmDelete() {
            if (!this.employee.id) return;

            this.isDeleting = true;
            this.successMessage = '';
            this.errorMessage = '';

            try {
                // Call the backend API
                await EmployeeApi.DeleteEmployee(this.employee.id);

                this.successMessage = 'Employee deleted successfully!';

                // Emit event to parent to refresh the table
                this.$emit('deleted');

                // Auto-close after brief delay for visual feedback
                setTimeout(() => {
                    this.closeDialog();
                }, 1000);
            } catch (err) {
                this.errorMessage =
                    err?.response?.data?.message ||
                    err.message ||
                    'Failed to delete employee.';
            } finally {
                this.isDeleting = false;
            }
        },
    },
};
</script>

<style scoped>
.delete-header {
    background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
}

.delete-dialog {
    border-top: 4px solid #d32f2f;
}

.flex-1-1 {
    flex: 1 1 0;
}
</style>
