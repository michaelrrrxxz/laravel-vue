<script setup>
import { ref, onMounted, nextTick } from "vue";
import api from "@/api/axiosInstance";
import Swal from "sweetalert2";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

// Course data
const courses = ref([]);
const modal = ref(null);
const isEditMode = ref(false);
const editingCourseId = ref(null);
const bsModal = ref(null);

// Form fields
const name = ref("");

// Fetch courses on mount
onMounted(async () => {
  await fetchCourses();
});

// Fetch courses
const fetchCourses = async () => {
  try {
    const response = await api.get("courses");
    courses.value = response.data;
  } catch (error) {
    console.error("Error fetching courses:", error);
  }
};

// Show modal for adding a course
const showModal = () => {
  resetForm();
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
  editingCourseId.value = null;
};

// Edit course
const editCourse = (course) => {
  resetForm();
  name.value = course.name;
  isEditMode.value = true;
  editingCourseId.value = course.id;
  nextTick(() => openModal());
};

// Save course (Create or Update)
const saveCourse = async () => {
  try {
    const courseData = { name: name.value.trim() };

    if (!courseData.name) {
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
      await api.put(`courses/${editingCourseId.value}`, courseData, config);
      Swal.fire({
        title: "Updated",
        text: "Course updated successfully.",
        icon: "success",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    } else {
      await api.post("courses", courseData, config);
      Swal.fire({
        title: "Created",
        text: "Course added successfully.",
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
    console.error("Error saving course:", error);
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

// Delete course (with SweetAlert2 confirmation)
const deleteCourse = async (courseId) => {
  const result = await Swal.fire({
    title: "Are you sure?",
    text: "This course will be permanently deleted!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!"
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`courses/${courseId}`);
      await refreshTable();
      Swal.fire({
        title: "Deleted",
        text: "The course has been deleted.",
        icon: "success",
        toast: true,
        position: "top-right",
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    } catch (error) {
      console.error("Error deleting course:", error);
      Swal.fire({
        title: "Error",
        text: "Failed to delete the course.",
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
  await fetchCourses();
  await nextTick(); // Ensure Vue processes reactivity updates
};
</script>

<template>
  <section class="content">
    <div class="container-fluid">
      <div class="content-card">
        <div class="card">
          <div class="card-header">
            <h1 class="card-title text-dark">Course List</h1>
            <div class="card-tools">
              <button class="btn btn-primary" @click="showModal">Add Course</button>
            </div>
          </div>

          <div class="card-body">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class="w-50">Course Name</th>
                  <th class="w-50">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="course in courses" :key="course.id">
                  <td>{{ course.name }}</td>
                  <td>
                    <button class="btn btn-primary me-2" @click="editCourse(course)">
                      <i class="fa fa-edit"></i> Edit
                    </button>
                    <span class="text-white">|</span>
                    <button class="btn btn-danger" @click="deleteCourse(course.id)">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                <tr v-if="courses.length === 0">
                  <td colspan="2" class="text-center">No courses found.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="courseModal" tabindex="-1" ref="modal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? "Edit Course" : "Add Course" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" @click="hideModal"></button>
          </div>

          <!-- Modal Body -->
          <div class="modal-body">
            <form @submit.prevent="saveCourse">
              <label class="fw-bold">Name</label>
              <input type="text" class="form-control mb-2" v-model="name" required />
            </form>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="hideModal">Close</button>
            <button class="btn btn-success" @click="saveCourse">
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
