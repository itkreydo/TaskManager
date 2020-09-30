import Home from "../components/Home";
import DashbroadLayout from "../components/layouts/DashbroadLayout";
import LoginComponent from "../components/auth/LoginComponent";
import Projects from "../components/dashbroad/Projects";
import Workers from "../components/dashbroad/Workers";
import Dashboard from "../components/dashbroad/Dashboard";
import TaskList from "../components/dashbroad/TaskList";
import Profile from "../components/dashbroad/Profile";
import WorkerProfile from "../components/dashbroad/workers/WorkerProfile";
// import Login from "../components/Auth/Login";
// import CabinetTemplate from "../components/Cabinet/CabinetTemplate";
// import CabinetMain from "../components/Cabinet/CabinetMain";
// import MainMap from '../components/Map/MainMap';
// import Settings from '../components/Cabinet/Settings';

export const routes = [
    {
        path: '/',
        component: LoginComponent,
        name:'main'
    },
    {
        path: '/auth/login',
        component: LoginComponent,
        name:'main'
    },
    {
        path: '/dashbroad',
        component: DashbroadLayout,
        name: 'login'
    },
    {
        path: '/settings',
        component: DashbroadLayout,
        name: 'settings',
        children: [
            {
                path: '/',
                component: Profile
            },
        ]
    },
    {
        path: '/projects',
        component: DashbroadLayout,
        name: 'projects',
        children: [
            {
                path: '/',
                component: Projects
            },
            {
                path: ':project_id/dashboard',
                component: Dashboard
            },
            {
                path: ':project_id/tasks',
                component: TaskList
            },
            // {
            //     path: 'map',
            //     component: MainMap,
            //     meta: {btn_search: true, btn_menu: true}
            // },

        ]
    },
    {
        path: '/workers',
        component: DashbroadLayout,
        name: 'workers',
        children: [
            {
                path: '/',
                component: Workers
            },
            {
                path: ':id',
                component: WorkerProfile
            },
            // {
            //     path: 'map',
            //     component: MainMap,
            //     meta: {btn_search: true, btn_menu: true}
            // },

        ]
    },
    // {
    //     path: '/cabinet',
    //     component: CabinetTemplate,
    //     meta: {
    //         requiresAuth: true
    //     },
    //     children: [
    //         {
    //             path: '/',
    //             component: CabinetMain
    //         },
    //         {
    //             path: 'map',
    //             component: MainMap,
    //             meta: {btn_search: true, btn_menu: true}
    //         },
    //         {
    //             path: 'settings',
    //             component: Settings,
    //             meta: {title: 'Настройки', btn_back: true, btn_logout: true}
    //         },
    //     ]
    // },
];
