import { createAsyncThunk } from '@reduxjs/toolkit';
import * as api from '../api';
import { setToken, setIsSubmitting, setUser } from './authSlice';

export const login = createAsyncThunk(
  'auth/login',
  async ({ email, password }, { dispatch, rejectWithValue }) => {
    try {
      dispatch(setIsSubmitting(true));
      const formData = new FormData();
      formData.append('email', email);
      formData.append('password', password);
      const response = await api.login(formData);
      const { token } = response.data;
      dispatch(setToken(token));
      dispatch(setIsSubmitting(false));
    } catch (error) {
      const responseError = error.response
        ? error.response.data
        : { error: 'Network Error' };
      dispatch(setIsSubmitting(false));
      return rejectWithValue(responseError);
    }
  },
);

export const register = createAsyncThunk(
  'auth/register',
  async (
    { firstName, lastName, email, password, avatar, photos },
    { dispatch, rejectWithValue },
  ) => {
    try {
      dispatch(setIsSubmitting(true));

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
      dispatch(setIsSubmitting(false));
    } catch (error) {
      const responseError = error.response
        ? error.response.data
        : { error: 'Network Error' };
      dispatch(setIsSubmitting(false));
      return rejectWithValue(responseError);
    }
  },
);

export const profile = createAsyncThunk(
  'auth/profile',
  async (_, { getState, dispatch, rejectWithValue }) => {
    try {
      const state = getState();
      const response = await api.me(state.auth.token);
      const { user } = response.data;
      dispatch(setUser(user));
    } catch (error) {
      const responseError = error.response
        ? error.response.data
        : { error: 'Network Error' };
      dispatch(setIsSubmitting(false));
      return rejectWithValue(responseError);
    }
  },
);
