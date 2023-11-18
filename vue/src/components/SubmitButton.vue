<template>
  <v-btn
    type="submit"
    color="primary"
    flat
    :disabled="isSubmitting"
  >
    {{ text }}
  </v-btn>
</template>
  
  <script>
  export default {
    props: {
      isSubmitting: {
        type: Boolean,
        default: false,
      },
      text: {
        type: String,
        default: 'Submit',
      },
    },
  }
  </script>
  