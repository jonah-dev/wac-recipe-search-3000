// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: ['@nuxt/ui'],
  css: ['~/assets/css/main.css'],
  devtools: {
    enabled: true,
  },
  ui: {
    fonts: false,
  },
  runtimeConfig: {
    public: {
      appName: process.env.APP_NAME,
      apiBaseUrl: process.env.API_BASE_URL,
    }
  },
})