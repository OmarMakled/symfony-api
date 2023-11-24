<template>
  <v-container>
    <v-row v-if="user">
      <v-col cols="12" md="3">
        <v-card class="text-center" flat>
          <v-avatar size="150" class="mx-auto mt-4">
            <img
              :src="user.avatar"
              :alt="user.first_name"
              style="object-fit: cover; width: 100%; height: 100%"
            />
          </v-avatar>
          <v-card-text class="text-left">
            <v-list>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>First Name:</v-list-item-title>
                  <v-list-item-subtitle>{{
                    user.first_name
                  }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Last Name:</v-list-item-title>
                  <v-list-item-subtitle>{{
                    user.last_name
                  }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Email:</v-list-item-title>
                  <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn color="primary" @click="onLogout"> Logout </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" md="9">
        <v-card flat class="mb-4">
          <v-card-text>
            <Slider :user="user" @delete="deletePhoto" />
          </v-card-text>
        </v-card>
        <UploadForm />
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import router from '../router';
import UploadForm from '../components/UploadForm.vue';
import Slider from '../components/PhotoSlider.vue';

export default {
  components: {
    UploadForm,
    Slider,
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
      isSubmitting: 'isSubmitting',
    }),
  },
  methods: {
    ...mapActions('auth', ['logout', 'deletePhoto']),
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
