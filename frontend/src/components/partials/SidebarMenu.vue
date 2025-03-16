<template>
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
    <li v-for="item in filteredMenuItems" :key="item.path" class="nav-item">
      <router-link :to="item.path" class="nav-link" :class="{ active: isActive(item.path) }">
        <i :class="['nav-icon', item.icon]"></i>
        <p>{{ item.label }}</p>
      </router-link>
    </li>
  </ul>
</template>

<script setup>
import { useRoute } from "vue-router";
import { computed } from "vue";

const props = defineProps({
  searchQuery: String, // Accept search query from parent component
});

const route = useRoute();

const isActive = (path) => route.path === path;

const menuItems = [
  // 📌 Core Navigation
  { label: "Dashboard", path: "/dashboard", icon: "fas fa-home" },

  // 👥 User Management
  { label: "Users", path: "/users", icon: "fas fa-users" },
  { label: "Enrolled Students", path: "/enrolled-students-list", icon: "fas fa-user-graduate" },

  // 🎓 Academic Management
  { label: "Course", path: "/course-list", icon: "fas fa-book" },
  { label: "Department", path: "/department-list", icon: "fas fa-building" },
  { label: "Batch", path: "/batch", icon: "fas fa-layer-group" },

  // 📋 Exams & Results
  { label: "Questions", path: "/questions", icon: "fas fa-question-circle" },
  { label: "Results", path: "/results", icon: "fas fa-chart-bar" },
  { label: "No Exams", path: "/no-exams-list", icon: "fas fa-ban" },

  // 📄 Miscellaneous
  { label: "List", path: "/list", icon: "fas fa-list" },
  { label: "Settings", path: "/settings", icon: "fas fa-cog" },
];


const filteredMenuItems = computed(() => {
  if (!props.searchQuery) return menuItems;
  return menuItems.filter((item) =>
    item.label.toLowerCase().includes(props.searchQuery.toLowerCase())
  );
});
</script>

<style scoped>
/* Active link (current page) */
.nav-link.active {
  background-color: #2ecc71 !important;
  color: white !important;
}

/* Hover effect only on text and icon */
.nav-link:hover i,
.nav-link:hover p ,
.nav-link:hover span {
  color: #2ecc71 !important;
}
</style>


