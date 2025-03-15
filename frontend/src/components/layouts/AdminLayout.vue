<template>
    <div class="wrapper">
      <Navbar />
      <Sidebar />
      <div class="content-wrapper">
        
        <router-view></router-view>
      </div>
      <Footer/>
    </div>
  </template>
  
  <script>
  import Sidebar from "../partials/Sidebar.vue";
  import Navbar from "../partials/Navbar.vue";
  import Footer from "../partials/Footer.vue";
  
  export default {
    components: { Sidebar, Navbar, Footer },
  };
  </script>
  
  <style>

  </style>
  