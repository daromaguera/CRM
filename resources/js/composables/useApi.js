import { useAuthUserStore } from '@/stores/authUser';
import { createFetch } from '@vueuse/core';
import { destr } from 'destr';

const authUserStore = useAuthUserStore();


export const useApi = createFetch({
  baseUrl: import.meta.env.VITE_API_BASE_URL || '/api',
  fetchOptions: {
    headers: {
      Accept: 'application/json',
    },
  },
  options: {
    refetch: true,
    beforeFetch({ options }) {
      const accessToken = authUserStore.authHeaders.Authorization;
      if (accessToken) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${accessToken}`,
        };
      }
    
      // Don't set Content-Type if body is FormData
      if (options.body instanceof FormData) {
        delete options.headers['Content-Type'];
      }
    
      return { options };
    }, 
    afterFetch(ctx) {
      const { data, response } = ctx

      // Parse data if it's JSON
      let parsedData = null
      try {
        parsedData = destr(data)
      }
      catch (error) {
        console.error(error)
      }
      
      return { data: parsedData, response }
    },
  },
})
