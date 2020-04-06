//A Router configuration for a Laravel-Vue SPA
//with separated frontend, using VUE CLI 3
//the details of the configuration can be found in 
//vue.config.js file

/*
The authentication is done in Login component,
by adding an authorization header to axios:
    axios.interceptors.request.use(function(config) {
        config.headers.Authorization = 'Bearer ' + localStorage.access_token
        return config
    })
*/

import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';

//Auth Components
import Login from './views/admin/Login.vue';
import axios from 'axios';

Vue.use(Router);

export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/pdf-to-jpg',
            name: 'pdf-to-jpg',
            component: () => import(/* webpackChunkName: "pdf-to-jpg" */ './views/PdfToJpg.vue')
        },
        {
            path: '/jpg-to-pdf',
            name: 'jpg-to-pdf',
            component: () => import(/* webpackChunkName: "jpg-to-pdf" */ './views/JpgToPdf.vue')
        },
        {
            path: '/merge-pdf',
            name: 'merge-pdf',
            component: () => import(/* webpackChunkName: "merge-pdf" */ './views/MergePdf.vue')
        },
        {
            path: '/split-pdf',
            name: 'split-pdf',
            component: () => import(/* webpackChunkName: "split-pdf" */ './views/SplitPdf.vue')
        },
        {
            path: '/blog',
            name: 'blog',
            component: () => import(/* webpackChunkName: "blog" */ './views/Blog.vue')
        },
        {
            path: '/admin/login',
            name: 'login',
            component: Login,
            beforeEnter: (to, from, next)=>{
                if (typeof localStorage.access_token !== 'undefined') {
                    return next({ name: 'dashboard' })
                }
                return next();
            },
        },
        {
            path: '/admin',
            name: 'dashboard',
            component: () => import(/* webpackChunkName: "dashboard" */ './views/admin/Dashboard.vue'),
            beforeEnter: (to, from, next)=>{
                if (typeof localStorage.access_token == 'undefined') {
                    return next({ name: 'login' })
                }
                return next();
            },
            meta: {
                layout: 'admin'
            },
        }
    ],
    scrollBehavior (to, from, savedPosition) {
        if(to.fullPath&&from.fullPath){
            if(savedPosition){
                savedPosition=null;
            }
        }
        return { x: 0, y: 0 };
    }
})
