<template>
    <div class="col-lg-3 col-md-6">
      <div :class="`card text-white shadow ${bgColor}`">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <h5 class="font-weight-bold">{{ count }}</h5>
              <p class="mb-0">{{ title }}</p>
            </div>
            <i :class="`${icon} fa-3x`"></i>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <span>More info</span>
          <i class="fas fa-arrow-circle-right"></i>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      count: {
        type: Number,
        required: true
      },
      title: {
        type: String,
        required: true
      },
      icon: {
        type: String,
        required: true
      },
      bgColor: {
        type: String,
        required: true
      }
    }
  };
  </script>
  
  <style scoped>
  .card {
    transition: transform 0.2s ease-in-out;
  }
  .card:hover {
    transform: scale(1.05);
  }
  </style>
  