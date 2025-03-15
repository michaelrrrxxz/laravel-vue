<template>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <div class="container">
      <router-link to="/" class="navbar-brand">Brand</router-link>

      <!-- Vue-controlled toggle button -->
      <button class="navbar-toggler" type="button" @click="isMenuOpen = !isMenuOpen">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Collapsible menu -->
      <div :class="['collapse navbar-collapse', { show: isMenuOpen }]" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <router-link to="/sign-in" class="nav-link btn btn-primary text-white">Login</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      isMenuOpen: false,
    };
  },
};
</script>

<style scoped>
.navbar {
  padding: 10px 20px;
}
.navbar-brand {
  font-size: 1.5rem;
  font-weight: bold;
}
.navbar-nav .nav-link {
  font-weight: 500;
}
</style>
