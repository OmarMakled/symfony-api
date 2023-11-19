import axios from 'axios';

const API_BASE_URL = 'http://localhost:8080/api';

const api = axios.create({
  baseURL: API_BASE_URL,
});

export const login = (userData) => api.post('/users/login', userData);
export const register = (userData) => api.post('/users/register', userData);
export const me = (token) =>
  api.get('/users/me', { headers: { Authorization: `Bearer ${token}` } });
