<script setup>
import { ref, onMounted, nextTick } from "vue";
import api from "@/api/axiosInstance";
import Swal from "sweetalert2";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

// Question data
const questions = ref([]);
const modal = ref(null);
const isEditMode = ref(false);
const editingQuestionId = ref(null);
const bsModal = ref(null);

// Form fields
const testType = ref("");
const questionText = ref("");
const questionImage = ref(null);
const optionA = ref("");
const optionAImage = ref(null);
const optionB = ref("");
const optionBImage = ref(null);
const optionC = ref("");
const optionCImage = ref(null);
const optionD = ref("");
const optionDImage = ref(null);
const optionE = ref("");
const optionEImage = ref(null);
const optionCorrect = ref("");
const ctype = ref("");
const isDeleted = ref(false);
const courseId = ref(null);

// Fetch data on mount
onMounted(async () => {
  await fetchQuestions();
});

// Fetch questions
const fetchQuestions = async () => {
  try {
    const response = await api.get("question");
    questions.value = response.data;
  } catch (error) {
    console.error("Error fetching questions:", error);
  }
};

// Show modal for adding a question
const showModal = () => {
  resetForm();
  isEditMode.value = false;
  nextTick(() => openModal());
};

// Open Bootstrap Modal
const openModal = () => {
  if (!bsModal.value) {
    bsModal.value = new bootstrap.Modal(document.getElementById("questionModal"), { keyboard: false });
  }
  bsModal.value.show();
};

// Close modal
const hideModal = () => {
  if (bsModal.value) {
    bsModal.value.hide();
  }
};

// Handle file upload
const handleFileUpload = (event, field) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = () => {
      switch (field) {
        case "questionImage": questionImage.value = reader.result; break;
        case "optionAImage": optionAImage.value = reader.result; break;
        case "optionBImage": optionBImage.value = reader.result; break;
        case "optionCImage": optionCImage.value = reader.result; break;
        case "optionDImage": optionDImage.value = reader.result; break;
        case "optionEImage": optionEImage.value = reader.result; break;
      }
    };
    reader.readAsDataURL(file);
  }
};

// Reset form
const resetForm = () => {
  testType.value = "";
  questionText.value = "";
  questionImage.value = null;
  optionA.value = "";
  optionAImage.value = null;
  optionB.value = "";
  optionBImage.value = null;
  optionC.value = "";
  optionCImage.value = null;
  optionD.value = "";
  optionDImage.value = null;
  optionE.value = "";
  optionEImage.value = null;
  optionCorrect.value = "";
  ctype.value = "";
  isDeleted.value = false;
  courseId.value = null;
  isEditMode.value = false;
  editingQuestionId.value = null;
};

// Save question (Create or Update)
const saveQuestion = async () => {
  try {
    const formData = new FormData();
    formData.append("test_type", testType.value);
    formData.append("question", questionText.value.trim());
    formData.append("question_image", questionImage.value);
    formData.append("option_a", optionA.value.trim());
    formData.append("option_a_image", optionAImage.value);
    formData.append("option_b", optionB.value.trim());
    formData.append("option_b_image", optionBImage.value);
    formData.append("option_c", optionC.value.trim());
    formData.append("option_c_image", optionCImage.value);
    formData.append("option_d", optionD.value.trim());
    formData.append("option_d_image", optionDImage.value);
    formData.append("option_e", optionE.value.trim());
    formData.append("option_e_image", optionEImage.value);
    formData.append("option_correct", optionCorrect.value);
    formData.append("ctype", ctype.value);
    formData.append("isDeleted", isDeleted.value);
    formData.append("courseId", courseId.value);

    const config = { headers: { "Content-Type": "multipart/form-data" } };

    if (isEditMode.value) {
      await api.put(`questions/${editingQuestionId.value}`, formData, config);
      Swal.fire("Updated!", "Question updated successfully.", "success");
    } else {
      await api.post("questions", formData, config);
      Swal.fire("Created!", "Question added successfully.", "success");
    }

    hideModal();
    await fetchQuestions();
  } catch (error) {
    console.error("Error saving question:", error);
    Swal.fire("Error!", "Something went wrong.", "error");
  }
};
</script>

<template>
  <section class="content">
    <div class="container-fluid">
      <div class="content-card">
        <div class="card">
          <div class="card-header">
            <h1 class="card-title text-dark">Question List</h1>
            <div class="card-tools">
              <button class="btn btn-primary" @click="showModal">Add Question</button>
            </div>
          </div>

          <div class="card-body">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Test Type</th>
                  <th>Question</th>
                  <th>Option A</th>
                  <th>Option B</th>
                  <th>Option C</th>
                  <th>Option D</th>
                  <th>Option E</th>
                  <th>Option Correct</th>
                  <th>CType</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="question in questions" :key="question.id">
                  <td>{{ question.test_type }}</td>
                  <td>
                    <img v-if="question.question_image" :src="question.question_image" width="80" />
                    <span v-else>{{ question.question }}</span>
                  </td>
                  <td>{{ question.option_a }}</td>
                  <td>{{ question.option_b }}</td>
                  <td>{{ question.option_c }}</td>
                  <td>{{ question.option_d }}</td>
                  <td>{{ question.option_e }}</td>
                  <td>{{ question.option_correct }}</td>
                  <td>{{ question.ctype }}</td>
                  <td>
                    <button class="btn btn-primary me-2" @click="editQuestion(question)">
                      <i class="fa fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-danger" @click="deleteQuestion(question.id)">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                <tr v-if="questions.length === 0">
                  <td colspan="10" class="text-center">No questions found.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="questionModal" tabindex="-1" ref="modal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? "Edit Question" : "Add Question" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" @click="hideModal"></button>
          </div>

          <div class="modal-body">
            <label>Test Type</label>
            <input type="text" class="form-control mb-2" v-model="testType" required />

            <label>Question (Text or Image)</label>
            <input type="text" class="form-control mb-2" v-model="questionText" />
            <input type="file" class="form-control mb-2" @change="(e) => handleFileUpload(e, 'questionImage')" />

            <label>Option Correct</label>
            <input type="text" class="form-control mb-2" v-model="optionCorrect" required />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
