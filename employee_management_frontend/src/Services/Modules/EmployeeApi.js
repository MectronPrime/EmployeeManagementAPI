import { api } from "../index";

export default {
  async getAllEmployeeDetails() {
    return await api.get("/employees/");
  },

  async CreateEmployee(payload) {
    return await api.post("/employees/", payload);
  },

  async UpdateEmployee(id, payload) {
    return await api.put(`/employees/${id}`, payload); 
  },

  async DeleteEmployee(id) {
    return await api.delete(`/employees/${id}`);
  },
  async SearchEmployee(){
    return await api.get(`/employess/search/`);

  }
};
