import { adminApi as api } from '@/api';

const state = {};

const mutations = {};

const actions = {
  async getUsers({ rootGetters }, { page }) {
    try {
      const response = await api.getUsers(rootGetters['auth/token'], { page });
      return response.data;
    } catch (error) {
      console.error('Fail:', error.message);
      throw error;
    }
  },
  async getUser({ rootGetters }, userId) {
    try {
      const response = await api.getUser(rootGetters['auth/token'], userId);
      const { user } = response.data;
      return user;
    } catch (error) {
      console.error('Fail:', error.message);
      throw error;
    }
  },
  async deletePhoto({ rootGetters }, photoId) {
    try {
      await api.deletePhoto(rootGetters['auth/token'], photoId);
    } catch (error) {
      console.error('Fail:', error.message);
      throw error;
    }
  },
  async deleteUser({ rootGetters }, userId) {
    try {
      await api.deleteUser(rootGetters['auth/token'], userId);
    } catch (error) {
      console.error('Fail:', error.message);
      throw error;
    }
  },
};

const getters = {};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
