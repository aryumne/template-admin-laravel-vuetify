import { apiVersion } from '@/configs'
import useAuthenticationStore from '@stores/authentication.js'
import axios from 'axios'

const blobApi = axios.create({
  baseURL: apiVersion,
  headers: {
    common: {
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/json',
    },
  },
  withCredentials: true,
})

blobApi.interceptors.response.use(
  res => res,
  err => {
    const authStore = useAuthenticationStore()
    const originalRequest = err.config
    if (err.response.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      authStore.signOut()
    } else if (err.response.status === 403) {
      window.location.href = '/forbidden'
    } else {
      return Promise.reject(
        err.response.data.hasOwnProperty('status') === true
          ? err.response.data
          : { status: false, message: err.response.statusText, data: [] },
      )
    }
  },
)


const downloadPdf = async (path, id, params = {}) => {
  try {
    const response = await blobApi.get(`/${path}/${id}`, { responseType: 'arraybuffer', ...params })

    console.log(response)
    
    const blob = new Blob([response.data], { type: 'application/pdf' })

    console.log(blob)
    
    return URL.createObjectURL(blob)
  } catch (error) {
    console.error('Error on service function')
    throw error
  }
}

const downloadExcel = async (path, body, params = {}) => {
  try {
    const response = await blobApi.post(`/${path}`, body, { responseType: 'blob', ...params })
    const blob = new Blob([response.data], { type: 'application/pdf' })
    
    return URL.createObjectURL(blob)
  } catch (error) {
    console.error('Error on service function')
    throw error
  }
}


export { downloadExcel, downloadPdf }

