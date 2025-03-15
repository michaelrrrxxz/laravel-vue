<script>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import api from "@/api/axiosInstance";
export default {
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      nameError: "",
      emailError: "",
      passwordError: "",
      loginError: "",
    };
  },
  methods: {
    validateName() {
      this.nameError = this.name.length < 3 ? "Name must be at least 3 characters." : "";
    },
    validateEmail() {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      this.emailError = !emailPattern.test(this.email) ? "Invalid email format." : "";
    },
    validatePassword() {
      this.passwordError =
        this.password.length < 6 ? "Password must be at least 6 characters." : "";
      
      // Confirm Password Validation
      if (this.password_confirmation && this.password !== this.password_confirmation) {
        this.passwordError = "Passwords do not match.";
      }
    },
    async register() {
  this.validateName();
  this.validateEmail();
  this.validatePassword();

  if (!this.nameError && !this.emailError && !this.passwordError) {
    const userData = {
      name: this.name,
      email: this.email,
      password: this.password,
      password_confirmation: this.password_confirmation,
    };

    try {
      const response = await api.post("/register", userData, {
        headers: { "Content-Type": "application/json" },
      });

      console.log("Registration successful:", response.data);
      alert("Registration successful! Please login.");
      this.$router.push("/sign-in"); // Redirect to login page
    } catch (error) {
      console.error("Registration failed:", error.response?.data || error.message);
      this.loginError = error.response?.data?.message || "Registration failed. Please try again.";
    }
  }
}

  },
};
</script>

<template>
  <div class="login-container">
    <h2 class="text-center">Register</h2>
    <form @submit.prevent="register">
      <!-- Name Field -->
      <input
        v-model="name"
        placeholder="Name"
        :class="{ 'is-invalid': nameError }"
        @input="validateName"
      />
      <small v-if="nameError" class="text-danger">{{ nameError }}</small>

      <!-- Email Field -->
      <input
        v-model="email"
        placeholder="Email"
        type="email"
        :class="{ 'is-invalid': emailError }"
        @input="validateEmail"
      />
      <small v-if="emailError" class="text-danger">{{ emailError }}</small>

      <!-- Password Field -->
      <input
        v-model="password"
        placeholder="Password"
        type="password"
        :class="{ 'is-invalid': passwordError }"
        @input="validatePassword"
      />
      <small v-if="passwordError" class="text-danger">{{ passwordError }}</small>

      <!-- Confirm Password Field -->
      <input
        v-model="password_confirmation"
        placeholder="Confirm Password"
        type="password"
        :class="{ 'is-invalid': passwordError }"
        @input="validatePassword"
      />

      <!-- Display General Form Error -->
      <small v-if="loginError" class="text-danger">{{ loginError }}</small>

      <button type="submit">Register</button>

      <!-- Login Link -->
      <p class="text-center mt-2">
        Have an account?
        <RouterLink to="/sign-in" class="text-primary">Login</RouterLink>
      </p>

      <!-- Forgot Password Link -->
      <p class="text-center">
        <RouterLink to="/forgot-password" class="forgot-password">Forgot Password?</RouterLink>
      </p>
    </form>
  </div>
</template>

<style scoped>
.login-container {
  max-width: 400px;
  margin: auto;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
  text-align: left;
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

.forgot-password {
  display: block;
  margin-top: 10px;
  text-align: center;
  color: #007bff;
  cursor: pointer;
  text-decoration: none;
}

.forgot-password:hover {
  text-decoration: underline;
}
</style>
