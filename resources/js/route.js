import Dashboard from "./components/Dashboard.vue";
import ListAppointment from "./pages/Appointments/ListAppointment.vue";
import ListUsers from "./pages/users/ListUsers.vue";
import Settings from "./pages/settings/Settings.vue";
import Profile from "./pages/profile/Profile.vue";

export default [
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
