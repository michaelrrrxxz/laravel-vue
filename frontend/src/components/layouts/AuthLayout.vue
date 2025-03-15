<template>
  <div class="auth-container">
    <div class="auth-content">
      <router-view></router-view>
    </div>
  </div>
</template>

<style scoped>
.auth-container {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  /* background-color: #f4f6f9; Light gray background */
}

.auth-content {
  /* background: white; */
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  width: 400px;
  text-align: center;
}
</style>
