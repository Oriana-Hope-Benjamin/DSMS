import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

// --- Route Definitions ---
const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/pages/Dashboard.vue'),
    meta: { requiresAuth: true }, 
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/pages/auth/Login.vue'),
    meta: { requiresAuth: false },
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/pages/auth/Register.vue'),
    meta: { requiresAuth: false }, 
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/pages/Dashboard.vue'),
    meta: { requiresAuth: true }, 
  },
  {
    path: '/branches',
    name: 'branches',
    component: () => import('@/views/pages/branches/index.vue'),
    meta: { requiresAuth: true }, 
  },
];

// --- Router Instance ---
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// -- Global Navigation Guard

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  const isGuestRoute = to.meta.requiresAuth === false;

  if (!authStore.attemptedFetch && !isGuestRoute) {
    try {
      await authStore.fetchUser();
    } catch (e) {
      console.error('Error in router guard fetchUser (this is expected on 401/419):', e);
    }
  }
  
  const requiresAuth = to.meta.requiresAuth;
  const isAuthenticated = authStore.isAuthenticated;

  if (requiresAuth && !isAuthenticated) {
    next({
      name: 'login',
      query: { redirect: to.fullPath },
    });
    return;
  }

  if (isGuestRoute && isAuthenticated) {
    next({ name: 'home' });
    return; 
  }

  next();
});


export default router;