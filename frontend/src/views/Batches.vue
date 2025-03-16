<script setup>
import { ref, onMounted, nextTick } from "vue";
import api from "@/api/axiosInstance";
import Swal from "sweetalert2";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

// Batch data
const batches = ref([]);
const modal = ref(null);
const isEditMode = ref(false);
const selectedBatch = ref(null);
const editingBatchId = ref(null);
const bsModal = ref(null);

// Form fields
const name = ref("");
const description = ref("");

// Fetch batches on mount
onMounted(async () => {
  await fetchBatches();
});

// Fetch batches
const fetchBatches = async () => {
  try {
    const response = await api.get("batch");
    batches.value = response.data;
  } catch (error) {
    console.error("Error fetching batches:", error);
  }
};

// Show modal for adding a batch
const showModal = () => {
  resetForm();
  selectedBatch.value = null;
  isEditMode.value = false;
  nextTick(() => openModal());
};

// Show batch details
const showBatch = async (batch) => {
  resetForm();
  selectedBatch.value = batch;
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
  description.value = "";
  isEditMode.value = false;
  editingBatchId.value = null;
};

// Edit batch
const editBatch = (batch) => {
  resetForm();
  selectedBatch.value = null;
  name.value = batch.name;
  description.value = batch.description;
  isEditMode.value = true;
  editingBatchId.value = batch.id;
  nextTick(() => openModal());
};

// Save batch (Create or Update)
const saveBatch = async () => {
  try {
    const batchData = { name: name.value.trim(), description: description.value.trim() };

    if (!batchData.name || !batchData.description) {
      Swal.fire({
        title: "Error",
        text: "Name and Description are required.",
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
      await api.put(`batch/${editingBatchId.value}`, batchData, config);
      Swal.fire({
        title: "Updated",
        text: "Batch updated successfully.",
        icon: "success",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    } else {
      await api.post("batch", batchData, config);
      Swal.fire({
        title: "Created",
        text: "Batch added successfully.",
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
    if (error.response && error.response.data.errors) {
      console.error("Validation Errors:", error.response.data.errors);
      Swal.fire({
        title: "Validation Error",
        text: Object.values(error.response.data.errors).flat().join("\n"),
        icon: "error",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    } else {
      console.error("Error saving batch:", error);
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
  }
};

// Delete batch (with SweetAlert2 confirmation)
const deleteBatch = async (batchId) => {
  const result = await Swal.fire({
    title: "Are you sure?",
    text: "This batch will be permanently deleted!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!"
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`batch/${batchId}`);
      await refreshTable();
      Swal.fire({
        title: "Deleted",
        text: "The batch has been deleted.",
        icon: "success",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    } catch (error) {
      console.error("Error deleting batch:", error);
      Swal.fire({
        title: "Error",
        text: "Failed to delete the batch.",
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
  await fetchBatches();
  await nextTick(); // Ensure Vue processes reactivity updates
};
</script>

<template>
  <section class="content">
    <div class="container-fluid">
      <div class="content-card">
        <div class="card">
          <div class="card-header">
            <h1 class="card-title text-dark">Batch List</h1>
            <div class="card-tools">
              <button class="btn btn-primary" @click="showModal">Add Batch</button>
            </div>
          </div>

          <div class="card-body">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class="w-50">Batch Name</th>
                  <th>Status</th>
                  <th class="w-50">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="batch in batches" :key="batch.id">
                  <td>{{ batch.name }}</td>
                  <td class="text-capitalize">{{ batch.status }}</td>
                  <td>
                    <button class="btn btn-success me-2" @click="showBatch(batch)">
                      <i class="fa fa-eye"></i> Show
                    </button>
                    <span class="text-white">|</span>

                    <button class="btn btn-primary me-2" @click="editBatch(batch)">
                      <i class="fa fa-edit"></i> Edit
                    </button>
                    <span class="text-white">|</span>
                    <button class="btn btn-danger" @click="deleteBatch(batch.id)">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                <tr v-if="batches.length === 0">
                  <td colspan="3" class="text-center">No batches found.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="batchModal" tabindex="-1" ref="modal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h5 class="modal-title">
              {{ isEditMode ? "Edit Batch" : selectedBatch ? "Batch Details" : "Add Batch" }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" @click="hideModal"></button>
          </div>

          <!-- Modal Body -->
          <div class="modal-body">
            <!-- Show Mode -->
            <div v-if="selectedBatch">
              <h4 class="text-primary fw-bold mb-3">{{ selectedBatch.name }}</h4>
              <p class="fw-semibold">{{ selectedBatch.description }}</p>

              <!-- Highlighted Access Key -->
              <div class="text-center p-3 rounded"
                style="background: linear-gradient(135deg, #007bff, #00c6ff); color: white; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);">
                <h5 class="mb-0">
                  <i class="fa fa-key fa-lg"></i>
                  <span class="fw-bold ms-1">{{ selectedBatch.access_key }}</span>
                </h5>
              </div>

              <!-- Highlighted Status (Locked/Active) -->
              <div class="text-center p-2 rounded mt-3" :style="selectedBatch.status === 'locked'
                ? 'background: linear-gradient(135deg, #dc3545, #ff6b6b); color: white;'
                : 'background: linear-gradient(135deg, #28a745, #4caf50); color: white;'">
                <h5 class="mb-0">
                  <i :class="selectedBatch.status === 'locked' ? 'fa fa-lock' : 'fa fa-unlock'"></i>
                  <span class="fw-bold ms-1 text-uppercase">{{ selectedBatch.status || 'active' }}</span>
                </h5>
              </div>

              <!-- Created & Updated Timestamps -->
              <div class="d-flex justify-content-between mt-3 text-muted small">
                <span><i class="fa fa-calendar"></i> Created: {{ new Date(selectedBatch.created_at).toLocaleString()
                  }}</span>
                <span><i class="fa fa-clock"></i> Updated: {{ new Date(selectedBatch.updated_at).toLocaleString()
                  }}</span>
              </div>
            </div>

            <!-- Add/Edit Mode -->
            <form v-else @submit.prevent="saveBatch">
              <label class="fw-bold">Name</label>
              <input type="text" class="form-control mb-2" v-model="name" required />

              <label class="fw-bold">Description</label>
              <textarea class="form-control" v-model="description"></textarea>
            </form>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="hideModal">Close</button>
            <button v-if="!selectedBatch" class="btn btn-success" @click="saveBatch">
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
