// https://nuxt.com/docs/api/configuration/nuxt-config
declare const process: { env: Record<string, string | undefined> }

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  nitro: {
    devProxy: {
      '/api': { target: process.env.API_PROXY_TARGET ?? 'http://localhost:8081/api', changeOrigin: true },
    },
  },
})
