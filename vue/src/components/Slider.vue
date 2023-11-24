<template lang="">
    <v-carousel v-if="user.photos && user.photos.length > 0">
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
        <v-btn
            icon
            class="delete-button"
            :disabled="isSubmitting"
            @click="onDelete(photo.id)"
        >
            <v-icon>mdi-delete</v-icon>
        </v-btn>
        </v-carousel-item>
    </v-carousel>
    <v-row v-else>
        <v-col>
        <p>No photos available.</p>
        </v-col>
    </v-row>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        user: {
            type: Object,
            default: true,
        }
    },
  computed: {
    ...mapGetters('auth', ['isSubmitting']),
  },
  methods: {
    onDelete(id){
        this.$emit('delete', id);
    }
  }
}
</script>

<style scoped>
.delete-button {
  position: absolute;
  top: 5px;
  right: 5px;
}
</style>