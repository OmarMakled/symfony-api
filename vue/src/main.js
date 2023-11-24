import { createApp } from 'vue'
import router from './router'
import store from './store'
import vuetify from './vuetify'
import App from './App.vue'
import SubmitButton from './components/SubmitButton.vue'
import ErrorList from './components/ErrorList.vue'

const app = createApp(App)
app.component("SubmitButton", SubmitButton)
app.component("ErrorList", ErrorList)
app.use(router)
app.use(vuetify)
app.use(store)
app.mount('#app')
