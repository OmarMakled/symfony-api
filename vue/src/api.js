// api.js

import axios from 'axios';
import store from './store';

const API_BASE_URL = 'http://localhost:8080/api';

const api = axios.create({
  baseURL: API_BASE_URL,
});

api.interceptors.request.use(
  function (config) {
    store.commit('setIsSubmitting', true);
    return config;
  },
  function (error) {
    return Promise.reject(error);
  }
);

api.interceptors.response.use(
  function (response) {
    store.commit('setIsSubmitting', false);
    store.commit('setResponseError', null);
    return response;
  },
  function (error) {
    const responseError = error.response ? error.response.data : { error: 'Network Error' };
    store.commit('setResponseError', responseError.error);
    store.commit('setIsSubmitting', false);
    return Promise.reject(error);
  }
);

export const authApi = {
  login: (userData) => api.post('/users/login', userData),
  register: (userData) => api.post('/users/register', userData),
  me: (token) => api.get('/users/me', { headers: { Authorization: `Bearer ${token}` } }),
  upload: (token, userData) => api.post('/photos', userData, { headers: { Authorization: `Bearer ${token}` } }),
  deletePhoto: (token, photoId) => api.delete(`/photos/${photoId}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  }),
};

export const adminApi = {
  getUsers: (token, { page }) => api.get(`/admin/users?page=${page}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  }),
  getUser: (token, userId ) => api.get(`/admin/users/${userId}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  }),
  deletePhoto: (token, photoId) => api.delete(`/admin/photos/${photoId}`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  }),
};
