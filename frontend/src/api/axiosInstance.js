import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  // baseURL: "https://ron-pki.online/api", 
  headers: {
    "Content-Type": "multipart/form-data",
  },
});

export default api;
