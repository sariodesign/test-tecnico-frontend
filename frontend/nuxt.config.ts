// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from '@tailwindcss/vite'
declare const process: { env: Record<string, string | undefined> }

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  runtimeConfig: {
    laravelApiUrl: process.env.API_PROXY_TARGET ?? 'http://localhost:8080/api',
  },

  nitro: {
    /* devProxy: {
      '/api': { target: process.env.API_PROXY_TARGET ?? 'http://localhost:8081/api', changeOrigin: true },
    }, */
  },

  vite: {
    plugins: [tailwindcss()],
  },

  modules: ['@nuxt/eslint'],
})