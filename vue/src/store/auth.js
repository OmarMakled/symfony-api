import { authApi as api } from '@/api';

const state = {
  token: localStorage.getItem('token') || null,
  user: JSON.parse(localStorage.getItem('user')) || null,
};

const mutations = {
  setToken(state, token) {
    state.token = token;
    localStorage.setItem('token', token);
  },
  clearToken(state) {
    state.token = null;
    state.user = null;
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  },
  setUser(state, user) {
    state.user = user;
    localStorage.setItem('user', JSON.stringify(user));
  },
  removePhotoFromUser(state, id) {
    const index = state.user.photos.findIndex((photo) => photo.id === id);
    if (index !== -1) {
      state.user.photos.splice(index, 1);
    }
  },
};

const actions = {
  async login({ commit }, { email, password }) {
    try {
      const formData = new FormData();
      formData.append('email', email);
      formData.append('password', password);
      const response = await api.login(formData);
      const { token } = response.data;
      commit('setToken', token);
    } catch ({ message }) {
      console.error('Fail:', message);
      throw error;
    }
  },
  async register(
    { commit },
    { firstName, lastName, email, password, avatar, photos },
  ) {
    try {
      const formData = new FormData();
      formData.append('first_name', firstName);
      formData.append('last_name', lastName);
      formData.append('email', email);
      formData.append('password', password);
      formData.append('avatar', avatar);

      if (photos) {
        for (const photo of photos) {
          formData.append('photos[]', photo);
        }
      }

      await api.register(formData);
    } catch (error) {
      console.error('Fail:', error.message);
      throw error;
    }
  },
  async update(
    { commit, state },
    { firstName, lastName, email, password, avatar },
  ) {
    try {
      const data = {
        first_name: firstName,
        last_name: lastName,
        email,
        password,
        avatar,
      };
      const response = await api.update(state.token, data);
      const { user } = response.data;
      commit('setUser', user);
    } catch (error) {
      console.error('Fail:', error.message);
      throw error;
    }
  },
  logout({ commit }) {
    commit('clearToken');
  },
  async profile({ commit }) {
    try {
      const response = await api.me(state.token);
      const { user } = response.data;
      commit('setUser', user);
    } catch ({ message }) {
      console.error('Fail:', message);
    }
  },
  async upload({ commit, state }, photos) {
    try {
      const formData = new FormData();
      for (const photo of photos) {
        formData.append('photos[]', photo);
      }
      const response = await api.upload(state.token, formData);
      const { user } = response.data;
      commit('setUser', user);
    } catch (error) {
      console.error('Fail:', error.message);
      throw error;
    }
  },
  async deletePhoto({ commit, state }, id) {
    try {
      await api.deletePhoto(state.token, id);
      commit('removePhotoFromUser', id);
    } catch (error) {
      console.error('Fail:', error.message);
      throw error;
    }
  },
};

const getters = {
  isAuth: (state) => !!state.token,
  token: (state) => state.token,
  user: (state) => state.user,
  isAdmin: (state) => state.user && state.user.isAdmin,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
