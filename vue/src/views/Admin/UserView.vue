<template>
  <v-container>
    <v-card
      flat
      title="Users"
    >
      <v-card-text v-if="user">
        {{ userId }} {{ user }}
        <Slider :user="user" @delete="deletePhoto"/>
      </v-card-text>
    </v-card>
  </v-container>
</template>

  
<script>
import { mapActions, mapGetters } from 'vuex'
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
    ...mapActions('auth', ['getUser']),
    async fetch(){
      this.user =  await this.getUser({userId: this.userId})
    },
    deletePhoto(id){
      console.log('delete', id)
    }
  },
  mounted() {
    this.fetch()
  }
}
</script>
  