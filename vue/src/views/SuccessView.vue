<template>
  <v-container>
    <v-row v-if="user">
      <v-col>
        <h4>Welcome {{ user.first_name }}</h4>
        <p>
          Click <router-link :to="{ name: 'profile' }">
            here
          </router-link> to go to profile
        </p>
      </v-col>
    </v-row>
  </v-container>
</template>  
<script>
import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters('auth', ['user']),
  }
}
</script>
  