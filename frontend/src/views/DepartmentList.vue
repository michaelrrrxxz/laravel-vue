<script setup>
import { ref, onMounted, nextTick } from "vue";
import api from "@/api/axiosInstance";
import Swal from "sweetalert2";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

// Departments data
const departments = ref([]);
const modal = ref(null);
const isEditMode = ref(false);
const selectedDepartment = ref(null);
const editingDepartmentId = ref(null);
const bsModal = ref(null);

// Form fields
const name = ref("");

// Fetch departments on mount
onMounted(async () => {
  await fetchDepartments();
});

// Fetch departments
const fetchDepartments = async () => {
  try {
    const response = await api.get("departments");
    departments.value = response.data;
  } catch (error) {
    console.error("Error fetching departments:", error);
  }
};

// Show modal for adding a department
const showModal = () => {
  resetForm();
  selectedDepartment.value = null;
  isEditMode.value = false;
  nextTick(() => openModal());
};

// Open Bootstrap Modal
const openModal = () => {
  if (!bsModal.value) {
    bsModal.value = new bootstrap.Modal(modal.value, { keyboard: false });
  }
  bsModal.value.show();
};

// Close modal
const hideModal = () => {
  if (bsModal.value) {
    bsModal.value.hide();
  }
};

// Reset form
const resetForm = () => {
  name.value = "";
  isEditMode.value = false;
  editingDepartmentId.value = null;
};

// Edit department
const editDepartment = (department) => {
  resetForm();
  name.value = department.name;
  isEditMode.value = true;
  editingDepartmentId.value = department.id;
  nextTick(() => openModal());
};

// Save department (Create or Update)
const saveDepartment = async () => {
  try {
    const departmentData = { name: name.value.trim() };

    if (!departmentData.name) {
      Swal.fire({
        title: "Error",
        text: "Name is required.",
        icon: "error",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
      return;
    }

    const config = { headers: { "Content-Type": "application/json", "Accept": "application/json" } };

    if (isEditMode.value) {
      await api.put(`departments/${editingDepartmentId.value}`, departmentData, config);
      Swal.fire({
        title: "Updated",
        text: "Department updated successfully.",
        icon: "success",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    } else {
      await api.post("departments", departmentData, config);
      Swal.fire({
        title: "Created",
        text: "Department added successfully.",
        icon: "success",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    }

    hideModal();
    await refreshTable();
  } catch (error) {
    console.error("Error saving department:", error);
    Swal.fire({
      title: "Error",
      text: "Something went wrong.",
      icon: "error",
      toast: true,
      position: "top-right",
      timer: 5000,
      timerProgressBar: true,
      showConfirmButton: false
    });
  }
};

// Delete department (with confirmation)
const deleteDepartment = async (departmentId) => {
  const result = confirm("Are you sure? This department will be permanently deleted!");

  if (result) {
    try {
      await api.delete(`departments/${departmentId}`);
      await refreshTable();
      Swal.fire({
        title: "Deleted",
        text: "The department has been deleted.",
        icon: "success",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    } catch (error) {
      console.error("Error deleting department:", error);
      Swal.fire({
        title: "Error",
        text: "Failed to delete the department.",
        icon: "error",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    }
  }
};

// Refresh Table
const refreshTable = async () => {
  await fetchDepartments();
  await nextTick();
};
</script>

<template>
  <section class="content">
    <div class="container-fluid">
      <div class="content-card">
        <div class="card">
          <div class="card-header">
            <h1 class="card-title text-dark">Department List</h1>
            <div class="card-tools">
              <button class="btn btn-primary" @click="showModal">Add Department</button>
            </div>
          </div>

          <div class="card-body">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class="w-50">Department Name</th>
                  <th class="w-50">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="department in departments" :key="department.id">
                  <td>{{ department.name }}</td>
                  <td>
                    <button class="btn btn-primary me-2" @click="editDepartment(department)">
                      <i class="fa fa-edit"></i> Edit
                    </button>
                    <span class="text-white">|</span>
                    <button class="btn btn-danger" @click="deleteDepartment(department.id)">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                <tr v-if="departments.length === 0">
                  <td colspan="2" class="text-center">No departments found.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="departmentModal" tabindex="-1" ref="modal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ isEditMode ? "Edit Department" : "Add Department" }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" @click="hideModal"></button>
          </div>

          <div class="modal-body">
            <form @submit.prevent="saveDepartment">
              <label class="fw-bold">Name</label>
              <input type="text" class="form-control mb-2" v-model="name" required />
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="hideModal">Close</button>
            <button class="btn btn-success" @click="saveDepartment">
              {{ isEditMode ? "Update" : "Submit" }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style>
.content-card {
  margin-top: 20px;
}
</style>