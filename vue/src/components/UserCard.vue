<template lang="">
  <v-card class="text-center" flat>
    <v-avatar size="150" class="mx-auto mt-4">
      <img
        :src="user.avatar"
        :alt="user.first_name"
        style="object-fit: cover; width: 100%; height: 100%"
      />
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
      <slot></slot>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  props: {
    user: {
      type: Object,
    },
  },
};
</script>
<style lang=""></style>
