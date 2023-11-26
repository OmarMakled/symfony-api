<template>
  <v-container>
    <v-row v-if="user">
      <v-col cols="12" md="3">
        <UserCard :user="user">
          <v-spacer />
          <v-btn color="primary" @click="onDeleteUser"> Delete </v-btn>
        </UserCard>
      </v-col>
      <v-col cols="12" md="9">
        <PhotoSlider :user="user" class="mb-5" @onDelete="onDeletePhoto" />
        <UpdateForm :user="user" class="mb-5" @onSubmit="update" />
        <UploadForm :user="user" class="mb-5" @onSubmit="upload" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions } from 'vuex';
import UserCard from '../../components/UserCard.vue';
import UpdateForm from '../../components/UpdateForm.vue';
import UploadForm from '../../components/UploadForm.vue';
import PhotoSlider from '../../components/PhotoSlider.vue';

export default {
  components: {
    UserCard,
    UpdateForm,
    UploadForm,
    PhotoSlider,
  },
  data() {
    return {
      user: null,
    };
  },
  computed: {
    userId() {
      return this.$route.params.userId;
    },
  },
  methods: {
    ...mapActions('admin', ['getUser', 'deletePhoto', 'deleteUser']),
    async onDeletePhoto(photoId) {
      await this.deletePhoto(photoId);
      const index = this.user.photos.findIndex((photo) => photo.id === photoId);
      if (index !== -1) {
        this.user.photos.splice(index, 1);
      }
    },
    async onDeleteUser() {
      await this.deleteUser(this.user.id);
      this.$router.go(-1);
    },
    async fetch() {
      this.user = await this.getUser(this.userId);
    },
  },
  mounted() {
    this.fetch();
  },
};
</script>
