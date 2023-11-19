import axios from 'axios'
import store from './store'

const API_BASE_URL = 'http://localhost:8080/api'

const api = axios.create({
  baseURL: API_BASE_URL,
})

api.interceptors.request.use(
  function (config) {
    store.commit('auth/setIsSubmitting', true)
    return config
  },
  function (error) {
    return Promise.reject(error)
  }
)
api.interceptors.response.use(
  function (response) {
    store.commit('auth/setIsSubmitting', false)
    store.commit('auth/setResponseError', null)
    return response
  },
  function (error) {
    const responseError = error.response ? error.response.data : { error: 'Network Error' }
    store.commit('auth/setResponseError', responseError.error)
    store.commit('auth/setIsSubmitting', false)
    return Promise.reject(error)
  }
)

export const login = (userData) => api.post('/users/login', userData)
export const register = (userData) => api.post('/users/register', userData)
export const me = (token) => api.get('/users/me', { headers: { Authorization: `Bearer ${token}` } })