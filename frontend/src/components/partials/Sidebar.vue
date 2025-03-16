<template>
  <aside class="main-sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
      <span class="brand-text font-weight-bold">BLUE</span>
    </a>

    <div class="sidebar">
      <!-- User Panel -->
      <div class="user-panel d-flex align-items-center">
        <div class="image">
          <img 
            :src="userAvatar" 
            class="img-circle elevation-2" 
            alt="User Avatar"
          />
        </div>
        <div class="info">
          <div 
            class="user-info" 
            @mouseover="showEmail = true" 
            @mouseleave="showEmail = false"
          >
            <a href="#" class="d-block user-name">
              {{ user?.name || "Guest" }}
            </a>
            <transition name="fade">
              <div v-if="showEmail" class="email-tooltip">
                {{ user?.email || "No email available" }}
              </div>
            </transition>
          </div>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="sidebar-search">
        <input
          type="text"
          v-model="searchQuery"
          class="form-control"
          placeholder="Search menu..."
        />
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <SidebarMenu :searchQuery="searchQuery" />
      </nav>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import SidebarMenu from "./SidebarMenu.vue";

const searchQuery = ref("");
const user = ref(null);
const showEmail = ref(false);

onMounted(() => {
  user.value = getUserFromLocalStorage();
});

// Computed property for user avatar
const userAvatar = computed(() => 
  user.value?.avatar || "https://via.placeholder.com/40"
);

// Function to get user data from local storage
function getUserFromLocalStorage() {
  try {
    const storedUser = localStorage.getItem("user");
    return storedUser ? JSON.parse(storedUser) : null;
  } catch (error) {
    console.error("Error parsing user data:", error);
    return null;
  }
}
</script>

<style scoped>
/* Sidebar */
.main-sidebar {
  width: 250px;
  height: 100vh;
  background: #fff;
  display: flex;
  flex-direction: column;
  border-right: 1px solid #ddd;
}

/* User Panel */
.user-panel {
  padding: 10px;
  border-bottom: 1px solid #ddd;
  display: flex;
  align-items: center;
}

.image img {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 50%;
}

/* Sidebar Search */
.sidebar-search {
  padding: 10px;
  border-bottom: 1px solid #ddd;
}

.sidebar-search input {
  width: 100%;
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

/* Email Tooltip */
.email-tooltip {
  position: absolute;
  top: 30px;
  left: 0;
  white-space: nowrap;
  background: rgba(0, 0, 0, 0.8);
  color: #fff;
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
  z-index: 1000;
}

/* Smooth tooltip transition */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease-in-out;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
