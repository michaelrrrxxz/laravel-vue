<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import api from "@/api/axiosInstance";
const email = ref("");
const password = ref("");
const emailError = ref("");
const passwordError = ref("");
const loginError = ref("");
const router = useRouter();
const user = ref(null);

const validateEmail = () => {
  emailError.value = !email.value
    ? "Email is required"
    : !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)
    ? "Invalid email format"
    : "";
};

const validatePassword = () => {
  passwordError.value = !password.value
    ? "Password is required"
    : password.value.length < 6
    ? "Password must be at least 6 characters"
    : "";
};

const login = async () => {
  loginError.value = "";
  validateEmail();
  validatePassword();

  if (emailError.value || passwordError.value) {
    return; // Stop login if validation fails
  }

  try {
    const response = await api.post("/login", {
      email: email.value,
      password: password.value,
    });

    if (response.data.user) {
      localStorage.setItem("token", response.data.token);
      localStorage.setItem("user", JSON.stringify(response.data.user));
      router.push("/dashboard").then(() => window.location.reload());
    } else {
      console.error("User data is missing from response");
    }
  } catch (error) {
    console.error("Login error:", error);
    loginError.value = "Invalid email or password";
  }
};


const getUser = () => {
  const storedUser = localStorage.getItem("user");
  if (storedUser) {
    user.value = JSON.parse(storedUser);
  }
};


getUser();
</script>

<template>
  <div class="login-container">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <input
        v-model="email"
        placeholder="Email"
        type="email"
        :class="{ 'is-invalid': emailError }"
        @input="validateEmail"
      />
      <small v-if="emailError" class="text-danger">{{ emailError }}</small>

      <input
        v-model="password"
        placeholder="Password"
        type="password"
        :class="{ 'is-invalid': passwordError }"
        @input="validatePassword"
      />
      <small v-if="passwordError" class="text-danger">{{ passwordError }}</small>

      <!-- Display Login Error -->
      <small v-if="loginError" class="text-danger">{{ loginError }}</small>

      <button type="submit">Login</button>
      <p class="text-center mt-2">
       
        <RouterLink to="/register" class="text-primary">Create Account</RouterLink>
      </p>
      <!-- Forgot Password Link -->
      <RouterLink to="/forgot-password" class="forgot-password">Forgot Password?</RouterLink>
    </form>
  </div>
</template>

<style scoped>
.login-container {
  max-width: 350px;
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
