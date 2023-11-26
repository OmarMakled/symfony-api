<template>
  <v-container>
    <v-row v-if="user">
      <v-col cols="12" md="3">
        <UserCard :user="user">
          <v-spacer />
          <v-btn color="primary" @click="onLogout"> Logout </v-btn>
        </UserCard>
      </v-col>
      <v-col cols="12" md="9">
        <PhotoSlider :user="user" class="mb-5" @onDelete="deletePhoto" />
        <UpdateForm :user="user" class="mb-5" @onSubmit="update" />
        <UploadForm :user="user" class="mb-5" @onSubmit="upload" />
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import router from '../router';
import UserCard from '../components/UserCard.vue';
import UpdateForm from '../components/UpdateForm.vue';
import UploadForm from '../components/UploadForm.vue';
import PhotoSlider from '../components/PhotoSlider.vue';

export default {
  components: {
    UserCard,
    UpdateForm,
    UploadForm,
    PhotoSlider,
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
      isSubmitting: 'isSubmitting',
    }),
  },
  methods: {
    ...mapActions('auth', ['logout', 'deletePhoto', 'update', 'upload']),
    onLogout() {
      this.logout();
      router.push('/');
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
