import { createRouter, createWebHistory } from "vue-router";
import DashbordComponent from "./components/Page/Dashbord.component.vue";
import DashbordComponentS from "./components/Page/DashbordSuper.vue";
import AutoEcoleComponent from "./components/Page/AutoEcole.component.vue";
import LoginComponent from "./components//Page/Logincomponent.vue";
import RegisterComponent from "./components//Page/Registercomponent.vue";
import CalendarComponent from "./components//Page/CalendarComponent.vue";
import DemandeComponent from "./components//Page/DemandeComponent.vue";
import UtilisateurComponent from "./components//Page/Utilisateurs.component.vue";
import Vehicule from "./components//Page/Vehicule.component.vue";
import Transaction from "./components//Page/Transaction.component.vue";
import Administrateur from "./components//Page/Admin.component.vue";
import PageNotFound from "./components/Page/PageNotFound.component.vue";
import Profile from "./components//Page/ProfilComponent.vue";
import Code from "./components//Page/CodeComponent.vue";
import Verif from "./components//Page/verifComponent.vue";
import Veriforcode from "./components//Page/VerifForCodeComponent.vue";
import codeforpass from "./components//Page/codeForPassword.vue";
import ressource from "./components//Page/Ressource.vue";
import permit from "./components//Page/PermitComponent.vue";

const routes = [
    { path: "/", component: permit },
    { path: "/permit", component: LoginComponent },
    { path: "/dashbord", component: DashbordComponent },
    { path: "/verif", component: Verif },
    { path: "/Veriforcode", component: Veriforcode },
    { path: "/Veriforcodeforpass", component: codeforpass },
    { path: "/email", component: Code },
    { path: "/dashbord_Super_Admin", component: DashbordComponentS },
    { path: "/autoEcole", component: AutoEcoleComponent },
    { path: "/login", component: LoginComponent },
    { path: "/Register", component: RegisterComponent },
    { path: "/Calendar", component: CalendarComponent },
    { path: "/demande", component: DemandeComponent },
    { path: "/vehicule", component: Vehicule },
    { path: "/profile", component: Profile },
    { path: "/transaction", component: Transaction },
    { path: "/admin", component: Administrateur },
    { path: "/:pathMatch(.*)*", component: PageNotFound },
    { path: "/utilisateur", component: UtilisateurComponent },
    { path: "/ressource", component: ressource },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    // Remove the 'selected' class from all sidebar items
    const sidebarItems = document.querySelectorAll(".sidebar-item");
    sidebarItems.forEach((item) => {
        item.classList.remove("selected");
    });

    // Add the 'selected' class to the sidebar item corresponding to the current route
    const currentPath = to.path;
    const currentSidebarItem = document.querySelector(
        `.sidebar-item [to="${currentPath}"]`
    );
    if (currentSidebarItem) {
        currentSidebarItem.closest(".sidebar-item").classList.add("selected");
    }

    next();
});

export default router;
