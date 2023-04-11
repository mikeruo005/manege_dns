import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import solution from '../views/solution.vue'
import download from '../views/download.vue'
import help from '../views/help.vue'
import helpList from '../views/helpList.vue'
import helpCont from '../views/helpCont.vue'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/solution',
    name: 'solution',
    component: solution
  },
  {
    path: '/download',
    name: 'download',
    component: download
  },
  {
    path: '/help',
    name: 'help',
    component: help
  },
  {
    path: '/helpList',
    name: 'helpList',
    component: helpList,
  },
  {
    path: '/helpCont',
    name: 'helpCont',
    component: helpCont,
  },
  // {
  //   path: '/activityRules',
  //   name: 'ActivityRules',
  //   component: () => import(/* webpackChunkName: "recruit" */ '../views/ActivityRules.vue')
  // }
  // {
  //   path: '/about',
  //   name: 'About',
  //   // route level code-splitting
  //   // this generates a separate chunk (about.[hash].js) for this route
  //   // which is lazy-loaded when the route is visited.
  //   component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  // }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

export default router
