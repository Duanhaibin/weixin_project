import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Search from '@/components/Search'
import Detail from '@/components/Detail'
import Assess from '@/components/Assess'
import Photolist from '@/components/Photolist'
import Success from '@/components/Success'
import Registered from '@/components/Registered'
import RegSuccess from '@/components/RegSuccess'
import NotFound from '@/components/NotFound'

import Monthlist from '@/components/Monthlist'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home
        },
        {
            path: '/mon/:id',
            name: 'Monthlist',
            component: Monthlist
        },

        {
            path: '/search',
            name: 'Search',
            component: Search
        },
        {
            path: '/s/:id',
            name: 'Detail',
            component: Detail,
            meta: {
              nonav: true
            }
        },
        {
            path: '/s/:id/assess',
            name: 'Assess',
            component: Assess,
            meta: {
              nonav: true
            }
        },
        {
            path: '/s/:id/photo',
            name: 'Photolist',
            component: Photolist,
            meta: {
              nonav: true
            }
        },
        {
            path: '/success',
            name: 'Success',
            component: Success,
            meta: {
              nonav: true
            }
        },

        {
            path: '/registered',
            name: 'Registered',
            component: Registered,
            meta: {
              nonav: true
            }
        },
        {
            path: '/regsuccess',
            name: 'RegSuccess',
            component: RegSuccess,
            meta: {
              nonav: true
            }
        },


        {
            path: '*',
            name: 'NotFound',
            component: NotFound
        }
    ]
})
