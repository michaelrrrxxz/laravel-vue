<template>
  <component :is="layout">
    <LoadingScreen :isLoading="isLoading" />
    <router-view v-if="!isLoading" />
  </component>
</template>

<script>
import AdminLayout from "./components/layouts/AdminLayout.vue";
import AuthLayout from "./components/layouts/AuthLayout.vue";
import LoadingScreen from "@/components/LoadingScreen.vue";

export default {
  computed: {
    layout() {
      return this.$route.meta.layout || "div"; 
    },
    bodyClass() {
      return this.$route.path === "/" ? "hold-transition layout-top-nav" : "hold-transition sidebar-mini";
    },
  },
  components: { AdminLayout, AuthLayout,  LoadingScreen  },
  data() {
    return {
      isLoading: true
    };
  },
  created() {
    setTimeout(() => {
      this.isLoading = false;
    }, 1000); // Adjust time as needed
  },
};
</script>
