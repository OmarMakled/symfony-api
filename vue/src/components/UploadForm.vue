<template>
  <v-card title="Upload" flat>
    <v-card-text>
      <v-form class="mt-4" @submit.prevent="onSubmit">
        <v-file-input
          v-model="photos"
          accept="image/*"
          label="Photos (If provided, minimum 4 images)"
          show-size
          multiple
          :rules="photoRules"
          variant="outlined"
        />
        <SubmitButton :is-submitting="isSubmitting" text="Upload" />
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  data() {
    return {
      photos: [],
      photoRules: [
        (v) => this.photos.length >= 4 || 'Please upload at least 4 images',
      ],
    };
  },
  computed: {
    ...mapGetters(['isSubmitting']),
  },
  methods: {
    onSubmit() {
      this.$emit('onSubmit', this.photos);
      this.photos = null;
    },
  },
};
</script>
