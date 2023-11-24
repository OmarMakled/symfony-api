<template>
  <v-container>
    <v-row>
      <v-col>
        <ErrorList :error="responseError" />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <RegisterForm />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex'
import RegisterForm from "@/components/RegisterForm.vue"

export default {
  components: {
    RegisterForm,
  },
  computed: {
    ...mapGetters(['responseError']),
  }
}
</script>
