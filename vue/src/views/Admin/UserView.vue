<template>
  <v-container>
    <v-card       flat 
      :title="user.full_name" class="mb-4" v-if="user">
          <v-card-subtitle>Email: {{ user.email }}</v-card-subtitle>
          <v-card-text>
            <v-list>
              <v-list-item>
                <v-list-item-content>First Name: {{ user.first_name }}</v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>Last Name: {{ user.last_name }}</v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>Is Active: {{ user.is_active ? 'Yes' : 'No' }}</v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-subtitle>Roles: {{ user.roles.join(', ') }}</v-card-subtitle>

          <v-card-actions>
            <v-btn @click="onDeleteUser(user.id)">Delete</v-btn>
          </v-card-actions>
        </v-card>
    <v-card
      flat
      class="mb-4"
      title="Users"
      v-if="user"    >
      <v-card-text>
        <Slider :user="user" @delete="onDeletePhoto"/>
      </v-card-text>
    </v-card>
  </v-container>
</template>

  
<script>
import { mapActions } from 'vuex'
import Slider from '../../components/Slider.vue';

export default {
  components: {
    Slider
  },
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
    ...mapActions('admin', ['getUser', 'deletePhoto', 'deleteUser']),
    async onDeletePhoto(photoId){
      await this.deletePhoto(photoId)
      const index = this.user.photos.findIndex(photo => photo.id === photoId)
      if (index !== -1) {
        this.user.photos.splice(index, 1)
      }
    },
    async onDeleteUser(userId){
      await this.deleteUser(userId)
      this.$router.go(-1);
    },
    async fetch(){
      this.user =  await this.getUser(this.userId)
    }
  },
  mounted() {
    this.fetch()
  }
}
</script>
  