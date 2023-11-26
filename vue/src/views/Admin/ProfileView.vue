<template>
  <v-container>
    <v-row v-if="user">
      <v-col cols="12" md="3">
        <UserCard :user="user">
          <v-spacer />
          <v-btn color="primary" @click="onLogout"> Logout </v-btn>
        </UserCard>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import UserCard from '../../components/UserCard.vue';

export default {
  components: {
    UserCard
  },
  computed: {
    ...mapGetters('auth', ['user']),
  },
  methods: {
    ...mapActions('auth', ['logout']),
    onLogout() {
      this.logout();
      this.$router.push('/');
    },
  },
};
</script>

<style scoped>
.delete-button {
  position: absolute;
  top: 5px;
  right: 5px;
}
</style>
