import axios from 'axios';

const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  withCredentials: true, // Required to send cookies
  withXSRFToken: true, // Required for Sanctum CSRF protection
  xsrfCookieName: 'XSRF-TOKEN', 
  xsrfHeaderName: 'X-XSRF-TOKEN',
});

  async (error) => {
    // Check CSRF Token Mismatch / Session Expired)
    if (error.response && (error.response.status === 401 || error.response.status === 419)) {
      
      try {
        const { useAuthStore } = await import('@/stores/auth');
        const authStore = useAuthStore();
       
        authStore.user = null;
        
        authStore.attemptedFetch = true; 
        
        const { default: router } = await import('@/router');
        
        if (router.currentRoute.value.name !== 'login') {
          console.warn('Axios interceptor: Session expired. Redirecting to login.');
          
          // Redirect to the login page
          await router.push({
            name: 'login',
        
            query: { redirect: router.currentRoute.value.fullPath },
          });
        }
      } catch (importError) {
        console.error("Error during interceptor dynamic import:", importError);
      }
    }

    return Promise.reject(error);
  }

export default axiosClient;