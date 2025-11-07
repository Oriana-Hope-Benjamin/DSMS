import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/pages/auth/Login.vue'
import Dashboard from '@/views/pages/Dashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard,
    },
    
    {
      path: '/login',
      name: 'login',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/pages/auth/Login.vue'),
    },
  ],
})

export default router
