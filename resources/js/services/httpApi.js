import { apiVersion } from '@configs'
import useAuthenticationStore from '@stores/authentication.js'
import axios from 'axios'

const httpApi = axios.create({
  baseURL: apiVersion,
  headers: {
    common: {
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/json',
      Accept: 'application/json',

      // 'Authorization': hashing.Decode(localStorage.getItem('_TOKEN')),
    },
  },
  withCredentials: true,
})

httpApi.interceptors.response.use(
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


const apiGet = async (path, params = {}) => {
  try {
    const response = await httpApi.get(`/${path}`, { ...params })
    
    return response?.data
  } catch (error) {
    console.error('Error on service function')
    throw error
  }
}

const apiGetOne = async (path, id, params = {}) => {
  try {
    const response = await httpApi.get(`/${path}/${id}`, { ...params })
    
    return response?.data
  } catch (error) {
    console.error('Error on service function')
    throw error
  }
}

const apiPost = async (path, body, params = {}) => {
  try {
    const response = await httpApi.post(`/${path}`, body, { ...params })
    
    return response?.data
  } catch (error) {
    console.error('Error on service function')
    throw error
  }
}

const apiPatch = async (path, id, body, params = {}) => {
  try {
    const response = await httpApi.patch(`/${path}/${id}`, body, { ...params })
    
    return response?.data
  } catch (error) {
    console.error('Error on service function')
    throw error
  }
}

const apiDelete = async (path, id, params = {}) => {
  try {
    const response = await httpApi.delete(`/${path}/${id}`, { ...params })
    
    return response?.data
  } catch (error) {
    console.error('Error on service function')
    throw error
  }
}

export { apiDelete, apiGet, apiGetOne, apiPatch, apiPost }

