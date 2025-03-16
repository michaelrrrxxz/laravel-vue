<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import api from "@/api/axiosInstance";
import Swal from "sweetalert2";
import { Modal } from "bootstrap";

const modal = ref(null);
const bsModal = ref(null);
const file = ref(null);
const fileType = ref("");
const isUploading = ref(false);
const students = ref([]);
const searchQuery = ref("");
const selectedCourse = ref("");
const sortKey = ref("name");
const sortOrder = ref("asc");
const isEditMode = ref(false);
const editingStudent = ref(null);
const name = ref("");
const course = ref("");
const idNumber = ref("");
const gender = ref("");

const showModal = () => {
    resetForm();
    nextTick(() => openModal());
};

const openModal = () => {
    if (!bsModal.value && modal.value) {
        bsModal.value = new Modal(modal.value, { keyboard: false });
    }
    bsModal.value?.show();
};

const hideModal = () => {
    bsModal.value?.hide();
};

const resetForm = () => {
    name.value = "";
    course.value = "";
    idNumber.value = "";
    gender.value = "";
    isEditMode.value = false;
    editingStudent.value = null;
};

const handleFileChange = (e) => {
    const selectedFile = e.target.files[0];

    if (!selectedFile) {
        file.value = null;
        fileType.value = "";
        return;
    }

    if (selectedFile.type !== "text/csv" && !selectedFile.name.endsWith(".csv")) {
        Swal.fire("Invalid File", "Please select a valid CSV file.", "warning");
        file.value = null;
        fileType.value = "";
        return;
    }

    file.value = selectedFile;
    fileType.value = "csv";
};

const uploadFile = async () => {
    if (!file.value) {
        Swal.fire("Error!", "Please select a CSV file to upload.", "error");
        return;
    }

    isUploading.value = true;
    const formData = new FormData();
    formData.append("file", file.value);

    try {
        const response = await api.post("/enrolledstudent/upload", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        students.value = response.data.uploaded_students || [];

        Swal.fire({
            title: "Success!",
            text: "File uploaded successfully!",
            icon: "success",
            toast: true,
            position: "top-right",
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: false
        });
        hideModal();
    } catch (error) {
        console.error("Upload error:", error.response?.data || error);
        Swal.fire({
            title: "Error!",
            text: "Failed to upload file.",
            icon: "error",
            toast: true,
            position: "top-right",
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    } finally {
        isUploading.value = false;
    }
};

// Fetch students from the backend
const fetchStudents = async () => {
    try {
        const response = await api.get("/enrolledstudent");
        students.value = response.data || [];
    } catch (error) {
        console.error("Error fetching students:", error);
    }
};

// Filtered and sorted students
const filteredStudents = computed(() => {
    return students.value
        .filter((student) =>
            student.name.toLowerCase().includes(searchQuery.value.toLowerCase()) &&
            (selectedCourse.value === "" || student.course === selectedCourse.value)
        )
        .sort((a, b) => {
            const aValue = a[sortKey.value]?.toString().toLowerCase() || "";
            const bValue = b[sortKey.value]?.toString().toLowerCase() || "";

            if (sortOrder.value === "asc") return aValue.localeCompare(bValue);
            return bValue.localeCompare(aValue);
        });
});

// Set sorting key
const setSort = (key) => {
    if (sortKey.value === key) {
        sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    } else {
        sortKey.value = key;
        sortOrder.value = "asc";
    }
};

// Create or update student
const saveStudent = async () => {
    try {
        const studentData = {
            name: name.value.trim(),
            course: course.value.trim(),
            id_number: idNumber.value.trim(),
            gender: gender.value.trim()
        };

        if (!studentData.name || !studentData.course || !studentData.id_number || !studentData.gender) {
            Swal.fire({
                title: "Error",
                text: "All fields are required.",
                icon: "error",
                toast: true,
                position: "top-right",
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false
            });
            return;
        }

        if (isEditMode.value) {
            await api.put(`/enrolledstudent/${editingStudent.value.id}`, studentData);
            Swal.fire({
                title: "Updated",
                text: "Student updated successfully.",
                icon: "success",
                toast: true,
                position: "top-right",
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        } else {
            await api.post("/enrolledstudent", studentData);
            Swal.fire({
                title: "Created",
                text: "Student added successfully.",
                icon: "success",
                toast: true,
                position: "top-right",
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        hideModal();
        await fetchStudents();
    } catch (error) {
        console.error("Error saving student:", error);
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

// Edit student
const editStudent = (student) => {
    resetForm();
    name.value = student.name;
    course.value = student.course;
    idNumber.value = student.id_number;
    gender.value = student.gender;
    isEditMode.value = true;
    editingStudent.value = student;
    nextTick(() => openModal());
};

// Delete student
const deleteStudent = async (studentId) => {
  const result = await Swal.fire({
    title: "Are you sure?",
    text: "This student will be permanently deleted!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!",
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`/enrolledstudent/${studentId}`);
      await fetchStudents(); // Refresh students list after deletion
      Swal.fire({
        title: "Deleted",
        text: "The student has been deleted.",
        icon: "success",
        toast: true,
        position: "top-right",
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
      });
    } catch (error) {
      console.error("Error deleting student:", error);
      Swal.fire({
        title: "Error",
        text: error.response?.data?.message || "Failed to delete the student.",
        icon: "error",
        toast: true,
        position: "top-right",
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
      });
    }
  }
};

// Fetch data when component is mounted
onMounted(fetchStudents);
</script>

<template>
    <section class="content">
        <div class="container-fluid">
            <div class="content-card">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title text-dark">Enrolled Student List</h1>
                        <div class="card-tools">
                            <button class="btn btn-primary" @click="showModal">Add Students</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search & Filter Controls -->
                        <div class="mb-3 d-flex gap-2">
                            <input v-model="searchQuery" class="form-control" placeholder="Search by name..." />
                            <select v-model="selectedCourse" class="form-select">
                                <option value="">All Courses</option>
                                <option v-for="(course, index) in [...new Set(students.map(s => s.course))]"
                                    :key="index" :value="course">
                                    {{ course }}
                                </option>
                            </select>
                        </div>

                        <!-- Student Table -->
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th @click="setSort('name')" class="sortable">Name</th>
                                    <th @click="setSort('course')" class="sortable">Course</th>
                                    <th @click="setSort('id_number')" class="sortable">ID Number</th>
                                    <th @click="setSort('gender')" class="sortable">Gender</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="student in filteredStudents" :key="student.id">
                                    <td>{{ student.name }}</td>
                                    <td>{{ student.course }}</td>
                                    <td>{{ student.id_number }}</td>
                                    <td>{{ student.gender }}</td>
                                    <td>
                                        <button class="btn btn-primary me-2" @click="editStudent(student)">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-danger" @click="deleteStudent(student.id)">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredStudents.length === 0">
                                    <td colspan="5" class="text-center">No students found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" ref="modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload CSV File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" @click="hideModal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="file" class="form-control" accept=".csv" @change="handleFileChange" />
                        <div v-if="fileType === 'csv'" class="file-icon mt-2 text-success">
                            <i class="fa-solid fa-file-csv"></i> CSV file selected
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="hideModal">Close</button>
                        <button class="btn btn-success" @click="uploadFile" :disabled="isUploading">
                            <span v-if="isUploading">Uploading...</span>
                            <span v-else>Upload</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.content {
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.container-fluid {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.file-icon {
    text-align: center;
    font-weight: bold;
}

.sortable {
    cursor: pointer;
}
</style>