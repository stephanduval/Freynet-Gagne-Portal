import { createFetch } from '@vueuse/core';
import { destr } from 'destr';

export const useApi = createFetch({
  baseUrl: import.meta.env.VITE_API_BASE_URL || '/api',
  fetchOptions: {
    headers: {
      Accept: 'application/json',
    },
  },
  options: {
    refetch: true,
    async beforeFetch({ options }) {
      // ✅ Get token from LocalStorage
      const accessToken = localStorage.getItem('accessToken');

      if (accessToken) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${accessToken}`,
        };
      }

      return { options };
    },
    afterFetch(ctx) {
      const { data, response } = ctx;

      // ✅ Parse JSON response safely
      let parsedData = null;
      try {
        parsedData = destr(data);
      } catch (error) {
        console.error(error);
      }

      return { data: parsedData, response };
    },
  },
});
