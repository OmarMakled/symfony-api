<template>
  <v-container>
    <v-card
      flat
      title="Users"
    >
      <v-card-text>
        <v-table>
          <thead>
            <tr>
              <th class="text-left">
                ID
              </th>
              <th class="text-left">
                Full Name
              </th>
              <th class="text-left">
                Email
              </th>
              <th class="text-left">
                Avatar
              </th>
              <th class="text-left">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="user in users"
              :key="user.id"
            >
              <td>{{ user.id }}</td>
              <td>{{ user.full_name }}</td>
              <td>{{ user.email }}</td>
              <td>
                <v-avatar
                  v-if="user.avatar"
                  :size="32"
                >
                  <v-img :src="user.avatar" />
                </v-avatar>
              </td>
              <td>
                <v-btn
                  small
                  flat
                  rounded
                  :to="{ name: 'admin-show-user', params: { userId: user.id } }"
                  class="mx-2"
                > 
                  Show
                </v-btn>
              </td>
            </tr>
          </tbody>
        </v-table>
        <v-pagination
          v-if="paginator"
          v-model="currentPage"
          :length="paginator.total_pages"
        />
      </v-card-text>
    </v-card>
  </v-container>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  data() {
    return {
      users: [],
      paginator: null,
      currentPage: 1,
    }
  },
  watch: {
    currentPage() {
      this.fetch()
    },
  },
  methods: {
    ...mapActions('admin', ['getUsers']),
    viewPhotos(user) {
      console.log('View photos for user:', user)
    },
    async fetch(){
      const { users, paginator } = await this.getUsers({page: this.currentPage})
      this.users = users
      this.paginator = paginator
    }
  },
  mounted() {
    this.fetch()  
  }
}
</script>
  