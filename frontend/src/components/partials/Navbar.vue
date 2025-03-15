<template>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown" @click="toggleDropdown" ref="dropdown">
        <a class="nav-link dropdown-toggle" href="#">
          <i class="fas fa-user"></i> {{ user?.name || "Guest" }}
        </a>
        <div v-if="dropdownVisible" class="dropdown-menu dropdown-menu-right show">
          <span class="dropdown-item text-muted">{{ user?.email || "No email" }}</span>
          <div class="dropdown-divider"></div>
          <a href="#" @click="logout" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const user = ref(null);
const dropdownVisible = ref(false);
const dropdown = ref(null);

onMounted(() => {
  const storedUser = localStorage.getItem("user");
  if (storedUser) {
    try {
      user.value = JSON.parse(storedUser);
    } catch (error) {
      console.error("Error parsing user data:", error);
      user.value = null;
    }
  }

  // Close dropdown when clicking outside
  document.addEventListener("click", closeDropdownOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", closeDropdownOutside);
});

const toggleDropdown = (event) => {
  event.stopPropagation(); // Prevent immediate closing
  dropdownVisible.value = !dropdownVisible.value;
};

const closeDropdownOutside = (event) => {
  if (dropdown.value && !dropdown.value.contains(event.target)) {
    dropdownVisible.value = false;
  }
};

const logout = () => {
  localStorage.removeItem("user");
  localStorage.removeItem("token");
  window.location.reload();
};
</script>

<style scoped>
/* Dropdown */
.dropdown-menu {
  position: absolute;
  right: 0;
  top: 40px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 5px;
  z-index: 1000;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  display: none;
}

.dropdown-menu.show {
  display: block;
}

.dropdown-item {
  padding: 8px 15px;
  display: block;
  color: #333;
  text-decoration: none;
}

.dropdown-item:hover {
  background: #f8f9fa;
}
</style>
