<template>
  <v-card title="Profile" flat>
    <v-card-text>
      <ErrorList :error="responseError" />
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
        <SubmitButton :is-submitting="isSubmitting" text="Save" />
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      firstName: '',
      lastName: '',
      email: '',
      password: '',
      avatar: '',
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
    };
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
      isSubmitting: 'isSubmitting',
      responseError: 'responseError',
    }),
  },
  methods: {
    ...mapActions('auth', ['update']),
    async submit() {
      await this.update({
        firstName: this.firstName,
        lastName: this.lastName,
        password: this.password,
        email: this.email,
        avatar: this.avatar,
      });
    },
  },
  created() {
    this.firstName = this.user.first_name;
    this.lastName = this.user.last_name;
    this.email = this.user.email;
    this.avatar = this.user.avatar;
  },
};
</script>
