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



const showModal = () => {
    file.value = null;
    fileType.value = "";
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
        const response = await api.post("/enrolledstudent", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        students.value = response.data.uploaded_students || [];
        
        Swal.fire("Success!", `File uploaded successfully!`, "success");
        hideModal();
    } catch (error) {
        console.error("Upload error:", error.response?.data || error);
        Swal.fire("Error!", "Failed to upload file.", "error");
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
            student.id_number.toLowerCase().includes(searchQuery.value.toLowerCase()) &&
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
                            <input v-model="searchQuery" class="form-control" placeholder="Search by ID number..." />
                            <select v-model="selectedCourse" class="form-select]">
                                <option value="">All Courses</option>
                                <option v-for="(course, index) in [...new Set(students.map(s => s.course))]" :key="index" :value="course">
                                    {{ course }}
                                </option>
                            </select>
                        </div>

                        <!-- Student Table -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th @click="setSort('id')" class="sortable">#</th>
                                    <th @click="setSort('name')" class="sortable">Student Name</th>
                                    <th @click="setSort('course')" class="sortable">Course</th>
                                    <th @click="setSort('year_level')" class="sortable">Exam Year</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(student, index) in filteredStudents" :key="index">
                                    <td>{{ student.id_number }}</td>
                                    <td>{{ student.name }}</td>
                                    <td>{{ student.course }}</td>
                                    <td>{{ new Date(student.created_at).getFullYear() }}</td>
                                </tr>
                                <tr v-if="filteredStudents.length === 0">
                                    <td colspan="4" class="text-center">No students found</td>
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
