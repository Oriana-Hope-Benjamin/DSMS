// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

// --- Route Definitions ---
const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/pages/Dashboard.vue'),
    meta: { requiresAuth: true }, // This route requires authentication
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/pages/auth/Login.vue'),
    meta: { requiresAuth: false }, // Guests only
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/pages/auth/Register.vue'),
    meta: { requiresAuth: false }, // Guests only
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/pages/Dashboard.vue'),
    meta: { requiresAuth: true }, // Another protected route
  },
  // Add other routes here...
];

// --- Router Instance ---
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// ----------------------------------------------------------------
// -- Global Navigation Guard
// ----------------------------------------------------------------
// src/router/index.js

// ...
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // --- 1. Fetch user on initial load ---
  
  // --- Key Change: Added check for guest route ---
  // We only try to fetch the user if:
  // 1. We haven't tried before (attemptedFetch is false)
  // 2. We are NOT navigating to a guest route (e.g., login or register)
  const isGuestRoute = to.meta.requiresAuth === false;

  if (!authStore.attemptedFetch && !isGuestRoute) {
    try {
      // fetchUser() will set attemptedFetch = true in its 'finally' block
      await authStore.fetchUser();
    } catch (e) {
      // Errors are handled by the interceptor
      console.error('Error in router guard fetchUser (this is expected on 401/419):', e);
    }
  }
  // -----------------------------------------------

  // --- 2. Auth Check ---
  // State is now up-to-date, or we've been redirected by the interceptor.
  const requiresAuth = to.meta.requiresAuth;
  const isAuthenticated = authStore.isAuthenticated;

  // --- 3. Redirect Logic ---

  // A. Trying to access a protected route without being authenticated
  if (requiresAuth && !isAuthenticated) {
    next({
      name: 'login',
      // Save the intended route in the query params
      query: { redirect: to.fullPath },
    });
    return; // Stop execution
  }

  // B. Trying to access a guest route (login/register) while authenticated
  if (isGuestRoute && isAuthenticated) {
    // Redirect to the home page
    next({ name: 'home' });
    return; // Stop execution
  }

  // C. All other cases
  next();
});
// ...

export default router;