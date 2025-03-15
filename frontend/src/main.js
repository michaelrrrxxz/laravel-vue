import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";


// import 'bootstrap/dist/css/bootstrap.min.css'; 
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';
const app = createApp(App);


router.beforeEach((to, from, next) => {
  if (to.path === "/") {
    document.body.className = "hold-transition layout-top-nav";
  } else {
    document.body.className = "hold-transition sidebar-mini";
  }
  next();
});


app.use(router).mount("#app");
