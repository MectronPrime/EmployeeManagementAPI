<template>
    <v-container class="pa-4">
        <h2>Add New Employee</h2>
        <p>Please fill in the details below to add a new employee.</p>

        <v-row justify="center">
            <v-col cols="12" md="6">
                <!-- form for adding new employee -->
                <v-form ref="form" v-model="formValid">
                    <v-text-field
                        label="Name"
                        v-model="employee.name"
                        required
                    />

                    <v-text-field
                        label="Email"
                        v-model="employee.email"
                        type="email"
                        required
                    />

                    <v-text-field
                        label="Phone"
                        v-model="employee.phone"
                        type="tel"
                        required
                    />


                    <v-select
                        label="Designation"
                        v-model="employee.designation"
                        :items="['Manager', 'Senior', 'Associate', 'Intern']"
                        required
                    />

                    <v-text-field
                        label="Monthly Salary Package"
                        v-model.number="employee.monthlySalaryPackage"
                        type="number"
                        min="0"
                        required
                    />

                    <v-text-field
                        label="Monthly Tax Value"
                        :value="monthlyTaxValue.toFixed(2)"
                        type="number"
                        readonly
                    />

                    <v-text-field
                        label="Yearly Increasing Bonus"
                        :value="yearlyIncreasingBonus.toFixed(2)"
                        type="number"
                        readonly
                    />

                    <v-text-field
                        label="Monthly Net Salary (Final)"
                        :value="monthlyNetSalary.toFixed(2)"
                        type="number"
                        readonly
                    />

                    <v-text-field
                        label="Yearly Net Salary"
                        :value="yearlyNetSalary.toFixed(2)"
                        type="number"
                        readonly
                    />

                    <v-btn
                        color="primary"
                        class="mt-4"
                        :disabled="isSubmitting || !formValid"
                        @click="addEmployee"
                    >
                        {{ isSubmitting ? 'Saving...' : 'Add Employee' }}
                    </v-btn>

                    <v-alert v-if="message" type="success" class="mt-4">
                        {{ message }}
                    </v-alert>

                    <v-alert v-if="error" type="error" class="mt-4">
                        {{ error }}
                    </v-alert>
                </v-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            employee: {
                name: '',
                email: '',
                phone: '',
                designation: 'Manager',
                monthlySalaryPackage: 0,
            },
            isSubmitting: false,
            formValid: false,
            message: '',
            error: '',
        };
    },

    // process calculation logic in frontend
    computed: {
        monthlyTaxValue() {
            const salary = Number(this.employee.monthlySalaryPackage) || 0;

            if (salary >= 150000) {
                return 150000 * 0.05; // 7,500
            }

            if (salary >= 100000) {
                return 150000 * 0.03; // 4,500
            }

            return 0;
        },

        yearlyIncreasingBonus() {
            const salary = Number(this.employee.monthlySalaryPackage) || 0;

            switch (this.employee.designation) {
                case 'Manager':
                    return salary * 0.05;
                case 'Senior':
                    return salary * 0.03;
                case 'Associate':
                    return salary * 0.01;
                default:
                    return 0;
            }
        },

        monthlyNetSalary() {
            const salary = Number(this.employee.monthlySalaryPackage) || 0;
            return Math.max(0, salary - this.monthlyTaxValue);
        },

        yearlyNetSalary() {
            return (this.monthlyNetSalary * 12) + this.yearlyIncreasingBonus;
        },
    },

    methods: {
        async addEmployee() {
            if (!this.$refs.form.validate()) {
                this.error = 'Please complete all required fields correctly.';
                return;
            }

            this.isSubmitting = true;
            this.message = '';
            this.error = '';

            const payload = {
                name: this.employee.name,
                email: this.employee.email,
                phone: this.employee.phone,
                designation: this.employee.designation,
                monthly_salary_package: Number(this.employee.monthlySalaryPackage),
                monthly_tax_value: this.monthlyTaxValue,
                yearly_increasing_bonus: this.yearlyIncreasingBonus,
                monthly_net_salary: this.monthlyNetSalary,
            };

            try {
                // const response = await fetch('/api/employees', {
                //     method: 'POST',
                //     headers: { 'Content-Type': 'application/json' },
                //     body: JSON.stringify(payload),
                // });

                //     if (!response.ok) {
                //         const body = await response.json().catch(() => ({}));
                //         throw new Error(body.message || 'Failed to create employee.');
                //     }

                this.message = 'Employee created successfully.';
                this.resetForm();
            } catch (err) {
                this.error = err.message || 'Something went wrong during save.';
            } finally {
                this.isSubmitting = false;
            }
        },

        //form reset after successful submission
        resetForm() {
            this.employee = {
                name: '',
                email: '',
                phone: '',
                designation: 'Manager',
                monthlySalaryPackage: 0,
            };
            this.$refs.form.reset();
        },
    },
};
</script>