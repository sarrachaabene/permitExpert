import "./bootstrap";
import { createApp } from "vue";
import router from "./router";
import axios from "axios";
import ExampleComponent from "./components/ExampleComponent.vue";
import LoginComponent from "./components/Page/Logincomponent.vue";
import RegisterComponent from "./components/Page/Registercomponent.vue";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import "@mdi/font/css/materialdesignicons.css";
import VueApexCharts from "vue3-apexcharts";
import CodeComponent from "./components/Page/CodeComponent.vue";
import VerifComponent from "./components/Page/verifComponent.vue";
import VerifforcodeComponent from "./components/Page/VerifForCodeComponent.vue";
import codeforpass from "./components//Page/codeForPassword.vue";
import password from "./components//Page/ChangeComponent.vue";
import permit from "./components//Page/PermitComponent.vue";
import index from "./components//Page/IndexComponent.vue";
import contact from "./components//Page/ContactComponent.vue";
// Set up axios globally:
window.axios = axios;
// Configure the default headers for axios:
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
// Define the base URL for all axios requests:
axios.defaults.baseURL = "http://localhost:8000/api";
const token = localStorage.getItem("token");

// If there's a token in the localStorage, set it as the default Authorization header:
if (token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

const vuetify = createVuetify({
    components,
    directives,
});

const app = createApp({
    router,
});
app.component("example-component", ExampleComponent);
app.component("login-component", LoginComponent);
app.component("register-component", RegisterComponent);
app.component("code-component", CodeComponent);
app.component("verif-component", VerifComponent);
app.component("verif-for-code-component", VerifforcodeComponent);
app.component("code-for-password-component", codeforpass);
app.component("change-component", password);
app.component("permit-component", permit);
app.component("index-component", index);
app.component("contact-component", contact);

app.use(VueApexCharts);
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem("token");
            axios.defaults.headers.common["Authorization"] = "Bearer";
            router.push({ name: "login" });
        }
        return Promise.reject(error);
    }
);
app.use(router).use(vuetify).mount("#app");
