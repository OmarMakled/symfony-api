import { createRouter, createWebHistory } from 'vue-router'
import store from './store'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('./views/HomeView.vue')
    },
    {
      path: '/register',
      name: 'register',
      meta: { requiresGuest: true },
      component: () => import('./views/RegisterView.vue')
    },
    {
      path: '/login',
      name: 'login',
      meta: { requiresGuest: true },
      component: () => import('./views/LoginView.vue')
    },
    {
      path: '/profile',
      name: 'profile',
      meta: { requiresAuth: true, requiresUser: true },
      component: () => import('./views/ProfileView.vue')
    },
    {
      path: '/admin',
      name: 'admin',
      meta: { requiresAuth: true, requiresAdmin: true },
      component: () => import('./views/Admin/HomeView.vue'),
      children: [
        {
          path: 'profile',
          name: 'admin-profile',
          meta: { requiresAuth: true, requiresAdmin: true },
          component: () => import('./views/Admin/ProfileView.vue')
        },
        {
          path: 'users',
          name: 'admin-users',
          meta: { requiresAuth: true, requiresAdmin: true },
          component: () => import('./views/Admin/UsersView.vue')
        },
      ]
    },
    {
      path: '/success',
      name: 'success',
      meta: { requiresAuth: true, requiresUser: true },
      component: () => import('./views/SuccessView.vue')
    },
  ]
})

router.beforeEach((to, from, next) => {
  const isAuth = store.getters['auth/isAuth']
  const isAdmin = store.getters['auth/isAdmin']

    // Check if route requires authentication and user is not authenticated
    if (to.meta.requiresAuth && !isAuth) {
      // Redirect to the home route if authentication is required but user is not authenticated
      next({ name: 'home' });
    } 
    // Check if route requires guest (not authenticated) and user is authenticated
    else if (to.meta.requiresGuest && isAuth) {
      // Redirect to the home route if guest access is required but user is authenticated
      next({ name: 'home' });
    } 
    // Check if route requires admin access and user is not an admin
    else if (to.meta.requiresAdmin && !isAdmin) {
      // Redirect to the home route if admin access is required but user is not an admin
      next({ name: 'home' });
    } 
    // Check if route requires user access and user is an admin
    else if (to.meta.requiresUser && isAdmin) {
      // Redirect to the home route if user access is required but user is an admin
      next({ name: 'home' });
    } 
    // If none of the conditions are met, allow the navigation to proceed
    else {
      next();
    }
})

export default router
