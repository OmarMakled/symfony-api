<template>
  <v-container>
    <v-card
      flat
      title="Users"
    >
      <v-card-text v-if="user">
        {{ userId }} {{ user }}
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
    ...mapActions('admin', ['getUser', 'deletePhoto']),
    async onDeletePhoto(photoId){
      await this.deletePhoto(photoId)
      const index = this.user.photos.findIndex(photo => photo.id === photoId)
      if (index !== -1) {
        this.user.photos.splice(index, 1)
      }
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
  