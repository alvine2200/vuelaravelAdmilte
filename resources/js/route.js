import Dashboard from "./components/Dashboard.vue";
import ListAppointment from "./pages/Appointments/ListAppointment.vue";
import CreateAppointment from "./pages/Appointments/CreateAppointment.vue";
import ListUsers from "./pages/users/ListUsers.vue";
import Settings from "./pages/settings/Settings.vue";
import Profile from "./pages/profile/Profile.vue";
import Login from "./pages/auth/Login.vue";

export default [
    {
        path: "/login",
        name: "admin.login",
        component: Login,
    },
    {
        path: "/admin/dashboard",
        name: "admin.dashboard",
        component: Dashboard,
    },
    {
        path: "/admin/appointments",
        name: "admin.appointments",
        component: ListAppointment,
    },
    {
        path: "/admin/appointments/create",
        name: "admin.appointments.create",
        component: CreateAppointment,
    },
    {
        path: "/admin/appointments/:id/edit",
        name: "admin.appointments.edit",
        component: CreateAppointment,
    },
    {
        path: "/admin/users",
        name: "admin.users",
        component: ListUsers,
    },
    {
        path: "/admin/settings",
        name: "admin.settings",
        component: Settings,
    },
    {
        path: "/admin/profile",
        name: "admin.profile",
        component: Profile,
    },
    {
        path: "/admin/logout",
        name: "admin.logout",
    },
];
