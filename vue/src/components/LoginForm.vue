<template>
  <v-card
    title="Login"
    flat
  >
    <v-card-text>
      <v-form
        class="mt-4"
        @submit.prevent="submit"
      >
        <v-text-field
          v-model="email"
          label="Email"
          :rules="emailRules"
          variant="outlined"
          required
        />
        <v-text-field
          v-model="password"
          label="Password"
          type="password"
          :rules="passwordRules"
          variant="outlined"
          required
        />
        <SubmitButton
          :is-submitting="isSubmitting"
          text="Login"
        />
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import router from '../router'

export default {
  data() {
    return {
      email: 'm@m.s',
      password: '1password',
      emailRules: [
        v => !!v || 'Email is required',
        v => /.+@.+\..+/.test(v) || 'Email must be valid',
      ],
      passwordRules: [
        v => !!v || 'Password is required',
      ],
    }
  },
  computed: {
    ...mapGetters('auth', ['isSubmitting', 'isAdmin']),
  },
  methods: {
    ...mapActions('auth', ['login', 'profile']),
    async submit(){
      await this.login({
        password: this.password, 
        email: this.email, 
      })
      await this.profile()
      if (this.isAdmin){
        router.push('/admin')
      }else {
        router.push('/profile')
      }
    }
  },
}
</script>
