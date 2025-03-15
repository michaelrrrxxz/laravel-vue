<template>
    <div class="d-flex vh-100 justify-content-center align-items-center bg-light text-center">
      <div>
        <h1 class="display-1 font-weight-bold text-danger">404</h1>
        <p class="lead text-muted">Oops! The page you're looking for doesn't exist.</p>
        <router-link to="/" class=" btn-lg mt-3">
          <i class="fas fa-home"></i> Go Back Home
        </router-link>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "NotFound",
  };
  </script>
  
  <style scoped>

  </style>
  