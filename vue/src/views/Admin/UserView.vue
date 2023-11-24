<template>
  <v-container>
    <v-card
      flat
      title="Users"
    >
      <v-card-text>
        {{ userId }} {{ user }}
      </v-card-text>
    </v-card>
  </v-container>
</template>

  
<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  data() {
    return {
      user: null,
    }
  },
  computed: {
    userId() {
      return this.$route.params.userId;
    },
  },
  methods: {
    ...mapActions('auth', ['getUser']),
    async fetch(){
      this.user =  await this.getUser({userId: this.userId})
    }
  },
  mounted() {
    this.fetch()
  }
}
</script>
  