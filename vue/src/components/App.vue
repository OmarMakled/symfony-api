<template>
  <v-app id="app">
    <v-app-bar
      class="px-3"
      flat
      density="compact"
    >
      <v-btn
        x-large
        rounded
        to="/"
        class="mx-2"
      >
        Home
      </v-btn>
      <v-btn
        v-if="!isAuth"
        x-large
        rounded
        to="/register"
        class="mx-2"
      >
        Register
      </v-btn>
      <v-btn
        v-if="!isAuth"
        x-large
        rounded
        to="/login"
        class="mx-2"
      >
        Login
      </v-btn>
      <v-btn
        v-if="user && isAdmin"
        x-large
        rounded
        to="/admin/users"
        class="mx-2"
      >
        Users
      </v-btn>
      <v-spacer />
      <v-btn
        v-if="user && !isAdmin"
        x-large
        rounded
        to="/profile"
        class="mx-2"
      > 
        Hi {{ user.first_name }}!
      </v-btn>
      <v-btn
        v-if="user && isAdmin"
        x-large
        rounded
        to="/admin/profile"
        class="mx-2"
      > 
        Hi {{ user.first_name }}!
      </v-btn>
    </v-app-bar>
    <v-main class="bg-grey-lighten-3">
      <v-container>
        <RouterView />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import {  RouterView } from 'vue-router'
import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters('auth', ['isAuth', 'user', 'isAdmin']),
  }
}
</script>