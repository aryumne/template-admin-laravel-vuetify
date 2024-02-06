/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'

import '@/plugins/axios'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import '@core-scss/template/index.scss'
import '@layouts/styles/index.scss'
import '@styles/styles.scss'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { createApp } from 'vue'
import print from 'vue3-print-nb'

loadFonts()


// Create vue app
const app = createApp(App)
const pinia = createPinia()

pinia.use(piniaPluginPersistedstate)


// Use plugins
app.use(vuetify)
app.use(pinia)
app.use(router)
app.use(print)

// Mount vue app
app.mount('#app')
