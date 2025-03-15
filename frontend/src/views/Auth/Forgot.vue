<script setup>
import { ref } from "vue";
import axios from "axios";

const email = ref("");
const message = ref("");
const emailError = ref("");

const validateEmail = () => {
  emailError.value = !email.value
    ? "Email is required"
    : !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)
    ? "Invalid email format"
    : "";
};

const sendResetLink = async () => {
  validateEmail();
  if (emailError.value) return;

  try {
    const response = await axios.post("http://127.0.0.1:8000/api/forgot-password", {
      email: email.value,
    });

    message.value = response.data.message || "If this email is registered, a reset link has been sent.";
  } catch (error) {
    console.error("Forgot Password Error:", error);
    message.value = "An error occurred. Please try again later.";
  }
};
</script>

<template>
  <div class="forgot-container">
    <h2>Forgot Password</h2>
    <p>Enter your email and we will send you a password reset link.</p>
    <form @submit.prevent="sendResetLink">
      <input
        v-model="email"
        placeholder="Enter your email"
        type="email"
        :class="{ 'is-invalid': emailError }"
        @input="validateEmail"
      />
      <small v-if="emailError" class="text-danger">{{ emailError }}</small>

      <button type="submit">Send Reset Link</button>

      <p v-if="message" class="text-success">{{ message }}</p>
    </form>

    <RouterLink to="/sign-in" class="back-to-login">Back to Login</RouterLink>
  </div>
</template>

<style scoped>
.forgot-container {
  max-width: 350px;
  margin: auto;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

input {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  width: 100%;
  margin-bottom: 10px;
  font-size: 1rem;
  transition: border-color 0.3s ease-in-out;
}

.is-invalid {
  border-color: red !important;
}

.text-danger {
  color: red;
  font-size: 0.9rem;
  margin-top: -5px;
  display: block;
}

.text-success {
  color: green;
  font-size: 0.9rem;
  margin-top: 10px;
}

button {
  padding: 10px;
  width: 100%;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
}

button:hover {
  background: #0056b3;
}

.back-to-login {
  display: block;
  margin-top: 10px;
  color: #007bff;
  text-decoration: none;
}

.back-to-login:hover {
  text-decoration: underline;
}
</style>
