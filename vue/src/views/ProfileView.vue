<template>
  <v-container>
    <v-row v-if="user">
      <v-col
        cols="12"
        md="3"
      >
        <v-card
          class="text-center"
          flat
        >
          <v-avatar
            size="150"
            class="mx-auto mt-4"
          >
            <img
              :src="user.avatar"
              :alt="user.first_name"
              style="object-fit: cover; width: 100%; height: 100%;"
            >
          </v-avatar>
          <v-card-text class="text-left">
            <v-list>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>First Name:</v-list-item-title>
                  <v-list-item-subtitle>{{ user.first_name }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Last Name:</v-list-item-title>
                  <v-list-item-subtitle>{{ user.last_name }}</v-list-item-subtitle>
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
            <v-btn
              color="primary"
              @click="onLogout"
            >
              Logout
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col
        cols="12"
        md="9"
      >
        <v-card flat>
          <v-card-text>
            <v-carousel v-if="user.photos">
              <v-carousel-item
                v-for="(photo, index) in user.photos"
                :key="index"
              >
                <v-img
                  :src="photo.url"
                  :alt="photo.name"
                  aspect-ratio="1"
                  cover
                  class="bg-grey-lighten-2"
                />
              </v-carousel-item>
            </v-carousel>
            <v-row v-else>
              <v-col>
                <p>No photos available.</p>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col
        cols="12"
      >
        <UploadForm />  
      </v-col>
    </v-row>
  </v-container>
</template>  
<script>
import { mapActions, mapGetters } from 'vuex'
import router from '../router'
import UploadForm from '../components/UploadForm.vue'

export default {
  components: {
    UploadForm
  },
  computed: {
    ...mapGetters('auth', ['user']),
  },
  methods: {
    ...mapActions('auth', ['logout']),
    onLogout(){
      this.logout()
      router.push('/')
    }
  }
}
</script>
  