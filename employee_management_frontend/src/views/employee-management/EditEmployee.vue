<template>
    <v-dialog v-model="dialog" max-width="520" persistent>
        <v-card rounded="lg" elevation="8">
            <!-- Dialog Header -->
            <v-card-title class="edit-dialog-title d-flex align-center ga-2 pa-5">
                <v-icon icon="mdi-account-edit" size="24" />
                Edit Employee
                <v-chip class="ml-2" size="small" color="white" variant="outlined">
                    {{ employee.name }}
                </v-chip>
            </v-card-title>

            <v-divider />

            <v-card-text class="pa-5">
                <v-form ref="editForm" v-model="formValid">

                    <!-- Phone -->
                    <v-text-field
                        label="Phone Number"
                        v-model="form.phone"
                        type="tel"
                        prepend-inner-icon="mdi-phone"
                        variant="outlined"
                        density="comfortable"
                        class="mb-3"
                        :rules="[v => !!v || 'Phone is required']"
                    />

                    <!-- Monthly Salary Package -->
                    <v-text-field
                        label="Monthly Salary Package"
                        v-model.number="form.monthlySalaryPackage"
                        type="number"
                        min="0"
                        prepend-inner-icon="mdi-cash"
                        variant="outlined"
                        density="comfortable"
                        class="mb-3"
                        :rules="[v => v >= 0 || 'Must be a positive value']"
                    />

                    <!-- Calculated Fields (read-only) -->
                    <v-row dense class="mt-1">
                        <v-col cols="12" sm="6">
                            <v-text-field
                                label="Monthly Tax Value"
                                :model-value="monthlyTaxValue.toFixed(2)"
                                prepend-inner-icon="mdi-calculator"
                                variant="filled"
                                density="comfortable"
                                readonly
                                bg-color="grey-lighten-4"
                                class="mb-3"
                            />
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                label="Yearly Increasing Bonus"
                                :model-value="yearlyIncreasingBonus.toFixed(2)"
                                prepend-inner-icon="mdi-trending-up"
                                variant="filled"
                                density="comfortable"
                                readonly
                                bg-color="grey-lighten-4"
                                class="mb-3"
                            />
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                label="Monthly Net Salary"
                                :model-value="monthlyNetSalary.toFixed(2)"
                                prepend-inner-icon="mdi-wallet"
                                variant="filled"
                                density="comfortable"
                                readonly
                                bg-color="green-lighten-5"
                                class="mb-3"
                            />
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                label="Yearly Net Salary"
                                :model-value="yearlyNetSalary.toFixed(2)"
                                prepend-inner-icon="mdi-bank"
                                variant="filled"
                                density="comfortable"
                                readonly
                                bg-color="green-lighten-5"
                                class="mb-3"
                            />
                        </v-col>
                    </v-row>

                    <!-- Alerts -->
                    <v-alert v-if="successMessage" type="success" variant="tonal" class="mb-2" closable>
                        {{ successMessage }}
                    </v-alert>
                    <v-alert v-if="errorMessage" type="error" variant="tonal" class="mb-2" closable>
                        {{ errorMessage }}
                    </v-alert>

                </v-form>
            </v-card-text>

            <v-divider />

            <!-- Dialog Actions -->
            <v-card-actions class="pa-4 ga-2">
                <v-spacer />
                <v-btn
                    variant="text"
                    color="grey-darken-1"
                    :disabled="isSaving"
                    @click="closeDialog"
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="primary"
                    variant="elevated"
                    :loading="isSaving"
                    :disabled="!formValid"
                    prepend-icon="mdi-content-save"
                    @click="saveChanges"
                >
                    Save Changes
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import EmployeeApi from '@/Services/Modules/EmployeeApi';

export default {
    name: 'EditEmployee',

    props: {
        // The full employee object selected for editing
        employee: {
            type: Object,
            required: true,
            default: () => ({}),
        },
        modelValue: {
            type: Boolean,
            default: false,
        },
    },

    emits: ['update:modelValue', 'saved'],

    data() {
        return {
            form: {
                phone: '',
                monthlySalaryPackage: 0,
            },
            formValid: false,
            isSaving: false,
            successMessage: '',
            errorMessage: '',
        };
    },

    computed: {
        dialog: {
            get() { return this.modelValue; },
            set(val) { this.$emit('update:modelValue', val); },
        },

        monthlyTaxValue() {
            const salary = Number(this.form.monthlySalaryPackage) || 0;
            if (salary >= 150000) return 150000 * 0.05;
            if (salary >= 100000) return 150000 * 0.03;
            return 0;
        },

        yearlyIncreasingBonus() {
            const salary = Number(this.form.monthlySalaryPackage) || 0;
            switch (this.employee.designation) {
                case 'Manager':   return salary * 0.05;
                case 'Senior':    return salary * 0.03;
                case 'Associate': return salary * 0.01;
                default:          return 0;
            }
        },

        monthlyNetSalary() {
            const salary = Number(this.form.monthlySalaryPackage) || 0;
            return Math.max(0, salary - this.monthlyTaxValue);
        },

        yearlyNetSalary() {
            return (this.monthlyNetSalary * 12) + this.yearlyIncreasingBonus;
        },
    },

    watch: {
        // Populate form fields whenever a new employee is passed in
        employee: {
            immediate: true,
            handler(emp) {
                if (emp && emp.id) {
                    this.form.phone = emp.phone || '';
                    this.form.monthlySalaryPackage = Number(emp.monthly_salary_package) || 0;
                }
            },
        },
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

        async saveChanges() {
            const { valid } = await this.$refs.editForm.validate();
            if (!valid) return;

            this.isSaving = true;
            this.successMessage = '';
            this.errorMessage = '';

            const payload = {
                phone: this.form.phone,
                monthly_salary_package: Number(this.form.monthlySalaryPackage),
                monthly_tax_value: this.monthlyTaxValue,
                yearly_increasing_bonus: this.yearlyIncreasingBonus,
                monthly_net_salary: this.monthlyNetSalary,
                yearly_net_salary: this.yearlyNetSalary,
            };

            try {
                await EmployeeApi.UpdateEmployee(this.employee.id, payload);
                this.successMessage = 'Employee updated successfully!';
                // Notify parent to refresh the table
                this.$emit('saved');
                // Auto-close after brief success feedback
                setTimeout(() => { this.closeDialog(); }, 1200);
            } catch (err) {
                this.errorMessage =
                    err?.response?.data?.message ||
                    err.message ||
                    'Failed to update employee.';
            } finally {
                this.isSaving = false;
            }
        },
    },
};
</script>

<style scoped>
.edit-dialog-title {
    background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}
</style>
