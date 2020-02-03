import Layout from "aj-vue-ui/src/components/layout/column_1";
import Header from "./Header";
import Footer from "@views/_partials/Footer";

import Home from "./Home";
import List from "./List";

const leadingName = "admin";

const routes = [
    {
        path: "/admin",
        component: Layout,
        props:{
            header:{
                component:Header
            },
            footer:{
                component:Footer,
            }
        },
        meta: {
            permission: "admin"
        },
        children: [
            {
                path: "",
                name: `${leadingName}:home`,
                component: Home
            },
            {
                path:'list',
                name:`${leadingName}:list`,
                component:List,

            }
        ]
    },
    {
        path: "/admin/login",
        name: `${leadingName}:login`,
        redirect: { name: `${leadingName}:home` }
    }
];
export default routes;
