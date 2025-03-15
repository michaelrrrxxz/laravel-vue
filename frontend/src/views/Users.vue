<script setup>
import { ref, onMounted, nextTick } from "vue";
import axios from "axios";
import api from "@/api/axiosInstance";


const users = ref([]);
const modal = ref(null);
const name = ref("");
const email = ref("");
const password = ref("");
const avatar = ref(null);
const avatarPreview = ref(null);
const isEditMode = ref(false);
const editingUserId = ref(null);
let bsModal = null;

onMounted(async () => {
  try {
    const response = await api.get("/users"); 
    users.value = response.data[0];
  } catch (error) {
    console.error("Error fetching users:", error);
  }

  await nextTick();
  if (modal.value) {
    bsModal = new bootstrap.Modal(modal.value, { keyboard: false });
  }
});

const resetForm = () => {
  name.value = "";
  email.value = "";
  password.value = "";
  avatar.value = null;
  avatarPreview.value = null;
  isEditMode.value = false;
  editingUserId.value = null;
};

const showModal = () => {
  resetForm();
  nextTick(() => {
    if (bsModal) bsModal.show();
  });
};

const hideModal = () => {
  if (bsModal) bsModal.hide();
};

const handleAvatarUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    avatar.value = file;
    avatarPreview.value = URL.createObjectURL(file);
  }
};

const saveUser = async () => {
  try {
    const formData = new FormData();
    formData.append("name", name.value);
    formData.append("email", email.value);
    if (password.value) formData.append("password", password.value);
    if (avatar.value) formData.append("avatar", avatar.value);

    if (isEditMode.value) {
        await api.put(`/users/${editingUserId.value}`, formData);

      const index = users.value.findIndex(user => user.id === editingUserId.value);
      if (index !== -1) {
        users.value[index] = {
          id: editingUserId.value,
          name: name.value,
          email: email.value,
          avatar: avatarPreview.value || users.value[index].avatar
        };
      }

    } else {
      const response = await api.post("/users", formData, {
        headers: { "Content-Type": "multipart/form-data" }
      });

      users.value.push(response.data);
    }

    hideModal();
  } catch (error) {
    console.error("Error saving user:", error.response?.data || error.message);
  }
};

const deleteUser = async (userId) => {
  if (!confirm("Are you sure you want to delete this user?")) return;

  try {
    await api.delete(`users/${userId}`);
    users.value = users.value.filter(user => user.id !== userId);
  } catch (error) {
    console.error("Error deleting user:", error);
  }
};

const editUser = async (userId) => {
  try {
    const response = await api.get(`/users/${userId}`);
    const userData = response.data.user; 

    if (!userData) {
      console.error("User data is empty.");
      return;
    }

    name.value = userData.name;
    email.value = userData.email;
    password.value = "";
    
   
    avatar.value = null; 
    avatarPreview.value = userData.avatar ? userData.avatar : null; 

    isEditMode.value = true;
    editingUserId.value = userId;

    nextTick(() => {
      if (bsModal) bsModal.show();
    });
  } catch (error) {
    console.error("Error fetching user data:", error);
  }
};


const showUser = (userId) => {
  alert("Show User ID: " + userId);
};
</script>
<template>
  <section class="content">
    <div class="container-fluid">
      <div class="content-card">
        <div class="card">
          <div class="card-header">
            <h1 class="card-title text-dark">Users List</h1>
            <div class="card-tools">
              <button class="btn btn-primary" @click="showModal">Add User</button>
            </div>
          </div>

          <div class="content-card">
            <div class="card-body">
              <table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th class="w-50">User</th>
      <th class="w-50">Options</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="user in users" :key="user.id">
      <td class="d-flex align-items-center">
        <img 
            :src="user.avatar ? user.avatar : '/img/image.png'" 
            alt="User Avatar" 
            class="rounded-circle me-3" 
            width="50" 
            height="50"
          />
        <div>
          {{ user.name }} <br />
          <small>{{ user.email }}</small>
        </div>
      </td>
      <td>
        <div class="d-flex justify-content-start align-items-center">
          <button class="btn btn-success d-flex align-items-center me-4" @click="showUser(user.id)">
            <i class="fa fa-eye me-2"></i> View
          </button>
          <span class="text-white">|</span>
          <button class="btn btn-primary d-flex align-items-center me-4" @click="editUser(user.id)">
            <i class="fa fa-pen me-2"></i> Edit
          </button>
          <span class="text-white">|</span>
          <button class="btn btn-danger d-flex align-items-center" @click="deleteUser(user.id)">
            <i class="fa fa-trash me-2"></i> Delete
          </button>
        </div>
      </td>
    </tr>
    <tr v-if="users.length === 0">
      <td colspan="2" class="text-center">No users found.</td>
    </tr>
  </tbody>
</table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" ref="modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ isEditMode ? "Edit User" : "Add User" }}</h5>
        <button type="button" class="btn-close" @click="hideModal"></button>
      </div>

      <div class="modal-body">
        <form @submit.prevent="saveUser">
          <!-- Avatar Upload -->
          <div class="text-center mb-3">
  <label class="avatar-upload">
    <input type="file" @change="handleAvatarUpload" accept="image/*" hidden />
    <img v-if="avatarPreview" :src="avatarPreview" class="avatar-preview" />
    <div v-else class="avatar-placeholder">
      <i class="fa fa-user"></i>
    </div>
  </label>
  <p class="text-muted small">Click to upload avatar</p>
</div>


          <!-- Name -->
          <label>Name</label>
          <input type="text" class="form-control" v-model="name">

          <!-- Email -->
          <label>Email</label>
          <input type="email" class="form-control" v-model="email">

          <!-- Password -->
          <label>Password</label>
          <input type="password" class="form-control" v-model="password">
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="hideModal">Close</button>
        <button type="button" class="btn btn-warning" @click="resetForm">Reset</button>
        <button class="btn btn-success" @click="saveUser">
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

.avatar-upload {
  display: inline-block;
  cursor: pointer;
  border-radius: 50%;
  overflow: hidden;
  width: 120px; /* Adjust size as needed */
  height: 120px;
  border: 2px solid #ddd;
}

.avatar-preview {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Ensures the image fits without distortion */
}

.avatar-placeholder {
  width: 100%;
  height: 100%;
  background: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: #aaa;
}
</style>

