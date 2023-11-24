<template>
  <v-container>
    <v-row>
      <v-col>
        <ErrorList :error="responseError" />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <LoginForm />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex';
import LoginForm from '@/components/LoginForm.vue';

export default {
  components: {
    LoginForm,
  },
  computed: {
    ...mapGetters(['responseError']),
  },
};
</script>
