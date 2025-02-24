import App from '@/App.vue'
import { registerPlugins } from '@core/utils/plugins'
import { createApp } from 'vue'
import Vue3Toastify from 'vue3-toastify'
import '/resources/js/echo.js' // Import echo.js

// Styles
import '@core-scss/template/index.scss'
import '@styles/styles.scss'
import 'vue3-toastify/dist/index.css'

// Create vue app
const app = createApp(App)

app.use(Vue3Toastify)

// Echo test
window.Echo.channel('test-channel').listen('TestEvent', (e) => {
  console.log('TestEvent received:', e)
})

// supress yellow warning on console
app.config.warnHandler = () => {}
// Register plugins
registerPlugins(app)

// Mount vue app
app.mount('#app')
