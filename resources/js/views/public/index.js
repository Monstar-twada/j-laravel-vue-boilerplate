import Layout from "aj-vue-ui/src/components/layout/column_1"
import Signin from './Signin';
import ChangePassword from './ChangePassword';
import ForgotPassword from './ForgotPassword';

export default [
    {
        path:'/',
        redirect:{name:'sign-in'}

    },
  {
    path: '/Signin',
    component: Layout,
    name:'sign-in',
    children:[
        {
            path:'',
            component:Signin
        }

    ]
  },
  {
    path: '/ChangePassword',
    component: Layout,
    name:'change-password',
    children:[
        {
            path:'',
            component:ChangePassword
        }

    ]
  },
  {
    path: '/ForgotPassword',
    component: Layout,
    name:'forgot-password',
    children:[
        {
            path:'',
            component:ForgotPassword
        }

    ]
  },
]
