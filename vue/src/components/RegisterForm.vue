<template>
  <v-card title="Register" flat>
    <v-card-text>
      <v-form class="mt-4" @submit.prevent="submit()">
        <v-text-field
          v-model="firstName"
          label="First Name"
          :rules="nameRules"
          variant="outlined"
        />
        <v-text-field
          v-model="lastName"
          label="Last Name"
          :rules="nameRules"
          variant="outlined"
        />
        <v-text-field
          v-model="email"
          label="Email"
          :rules="emailRules"
          variant="outlined"
        />
        <v-text-field
          v-model="password"
          label="Password"
          type="password"
          :rules="passwordRules"
          variant="outlined"
        />
        <v-text-field
          v-model="avatar"
          label="Avatar"
          :rules="avatarRules"
          variant="outlined"
        />
        <v-file-input
          v-model="photos"
          accept="image/*"
          label="Photos (If provided, minimum 4 images)"
          show-size
          multiple
          :rules="photoRules"
          variant="outlined"
        />
        <SubmitButton :is-submitting="isSubmitting" text="Register" />
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import router from '../router';

export default {
  data() {
    return {
      firstName: 'foo',
      lastName: 'bar',
      email: 'foo.bar@mail.com',
      password: 'password1',
      avatar: '',
      photos: null,
      nameRules: [
        (v) => !!v || 'Name is required',
        (v) =>
          (v && v.length >= 2 && v.length <= 25) ||
          'Name must be between 2 and 25 characters',
        (v) =>
          /^[a-zA-Z]+$/.test(v) || 'Only alphabetical characters are allowed',
      ],
      emailRules: [
        (v) => !!v || 'Email is required',
        (v) => /.+@.+\..+/.test(v) || 'Enter a valid email',
      ],
      passwordRules: [
        (v) => !!v || 'Password is required',
        (v) =>
          (v.length >= 6 && v.length <= 50) ||
          'Password must be between 6 and 50 characters',
        (v) => /\d/.test(v) || 'Password must contain at least one number',
      ],
      avatarRules: [
        (v) =>
          !v ||
          /^https?:\/\/.+\..+/.test(v) ||
          'Enter a valid URL for the avatar',
      ],
      photoRules: [
        (v) =>
          !this.photos ||
          this.photos.length >= 4 ||
          'Please upload at least 4 images',
      ],
    };
  },
  computed: {
    ...mapGetters(['isSubmitting']),
  },
  methods: {
    ...mapActions('auth', ['login', 'register', 'profile']),
    async submit() {
      await this.register({
        firstName: this.firstName,
        lastName: this.lastName,
        password: this.password,
        email: this.email,
        avatar: this.avatar,
        photos: this.photos,
      });
      await this.login({ email: this.email, password: this.password });
      await this.profile();
      router.push('/success');
    },
  },
};
</script>
