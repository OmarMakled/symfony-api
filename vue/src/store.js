import { createStore } from 'vuex';
import auth from './store/auth';

const state = {
  isSubmitting: false,
  responseError: null,
};

const mutations = {
  setIsSubmitting(state, isSubmitting) {
    state.isSubmitting = isSubmitting;
  },
  setResponseError(state, responseError) {
    state.responseError = responseError;
  },
};

const getters = {
  responseError: (state) => state.responseError,
  isSubmitting: (state) => state.isSubmitting,
};

const store = createStore({
  modules: {
    auth,
  },
  state,
  mutations,
  getters,
});

export default store;
