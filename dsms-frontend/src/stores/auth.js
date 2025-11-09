import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import axiosClient from '@/api/axios';

export const useAuthStore = defineStore('auth', () => {
  //states
  const user = ref(null); 
  const errors = ref({}); 
  const loading = ref(false); 
  const attemptedFetch = ref(false); 

  //Getters 
  const isAuthenticated = computed(() => !!user.value);

  // --- Actions ---

  //Fetch CSRF Cookie
  async function getCsrfCookie() {
    try {
      await axiosClient.get('/sanctum/csrf-cookie');
    } catch (error) {
      console.error('Error fetching CSRF cookie:', error);
    }
  }

  /**
   * Fetches the currently authenticated user.
   * If successful, updates the 'user' state.
   */
  async function fetchUser() {
    try {
      const { data } = await axiosClient.get('api/user');
      user.value = data;
    } catch (error) {
      
      console.error('Error fetching user:', error);
      user.value = null;
    } finally {
      
      attemptedFetch.value = true;
    }
  }

  /**
   * Handles user login.
   * @param {object} credentials 
   */
  async function login(credentials) {
    loading.value = true;
    errors.value = {};
    
    await getCsrfCookie();

    try {
      await axiosClient.post('api/login', credentials);
      // On success, fetch the user to update state
      await fetchUser();
    } catch (error) {
      if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors;
      } else {
        console.error('Login failed:', error);
      }
    } finally {
      loading.value = false;
    }
  }

  /**
   * Handles user registration.
   * @param {object} details 
   */
  async function register(details) {
    loading.value = true;
    errors.value = {};

    await getCsrfCookie();

    try {
      await axiosClient.post('api/register', details);
      await fetchUser();
    } catch (error) {
      if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors;
      } else {
        console.error('Registration failed:', error);
      }
      throw error;
    } finally {
      loading.value = false;
    }
  }

  /**
   * Handles user logout.
   */
  async function logout() {
    loading.value = true;
    errors.value = {};
    
    try {
      await axiosClient.post('api/logout');
    } catch (error) {
      console.error('Logout failed:', error);
    } finally {
      //clearing the user state
      user.value = null;
      attemptedFetch.value = false; 
      loading.value = false;
    }
  }

  return {
    // State
    user,
    errors,
    loading,
    attemptedFetch,
    // Getters
    isAuthenticated,
    // Actions
    getCsrfCookie,
    fetchUser,
    login,
    register,
    logout,
  };
});