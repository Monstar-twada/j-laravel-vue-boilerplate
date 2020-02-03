import Layout from "aj-vue-ui/src/components/layout/column_1";
import Header from "@views/_partials/Header";
import Footer from "@views/_partials/Footer";

import Signin from "./Signin";
import ChangePassword from "./ChangePassword";
import ForgotPassword from "./ForgotPassword";
import RegistrationForm from "./RegistrationForm";
import Home from "./Home";

export default [
    {
        path: "/",
        component: Layout,
        props:{
            header:{
                component:Header,
            },
            footer:{
                component:Footer,
            }
        },
        children: [
            {
                path: "",
                redirect: { name: "home" }
            },
            {
                path: "home",
                component: Home,
                name: "home",
                meta: {
                    permission: "authenticated"
                },
            },
            {
                path: "/sign-out",
                name: "sign-out",
                beforeEnter(to, from, next) {
                    Vue.auth.logout();
                    next({ name: "sign-in" });
                }
            },
            {
                path: "/login",
                name: "login",
                redirect: { name: "home" }
            },
            {
                path: "/sign-in",
                component: Signin,
                name: "sign-in"
            },
            {
                path: "/change-password",
                component: ChangePassword,
                name: "change-password"
            },
            {
                path: "/forgot-password",
                component: Layout,
                component: ForgotPassword,
                name: "forgot-password"
            },
            {
                path: "/registration-form",
                component: Layout,
                component: RegistrationForm,
                name: "registration-form"
            }
        ]
    }
];
