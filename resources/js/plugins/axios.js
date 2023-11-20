import { baseUrl } from '@configs'
import useAuthenticationStore from '@stores/authentication'
import axios from 'axios'

window.axios = axios

window.axios.defaults.baseUrl = baseUrl
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['Content-Type'] = 'application/json'
window.axios.defaults.headers.common['Accept'] = 'application/json'
window.axios.defaults.withCredentials = true

window.axios.interceptors.response.use(
  res => res,
  err => {
    const authStore = useAuthenticationStore()
    if (err.response.status === 401) {
      authStore.signOut()
    }
    
    // eslint-disable-next-line sonarjs/no-collapsible-if
    if (err.response.status === 403) {
      if (authStore.authenticated) this.$router.push({ name: 'forbidden' })
    }
    
    return Promise.reject(err)
  },
)
