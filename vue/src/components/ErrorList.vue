<template>
  <v-alert
    v-if="error"
    type="error"
    class="mb-2"
  >
    <ol>
      <li v-if="isStringError">
        {{ error }}
      </li>
      <li
        v-for="(messages, key) in error"
        v-else
        :key="key"
      >
        <ol>
          <li
            v-for="message in messages"
            :key="message"
          >
            {{ message }}
          </li>
        </ol>
      </li>
    </ol>
  </v-alert>
</template>

<script>
export default {
  props: {
    error: {
      type: [Object, String],
      default: null,
    },
  },
  computed: {
    isStringError() {
      return typeof this.error === 'string'
    },
  },
}
</script>
