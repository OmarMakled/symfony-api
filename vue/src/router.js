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
      meta: { requiresAuth: true },
      component: () => import('./views/ProfileView.vue')
    },
    {
      path: '/success',
      name: 'success',
      meta: { requiresAuth: true },
      component: () => import('./views/SuccessView.vue')
    },
  ]
})

router.beforeEach((to, from, next) => {
  const isAuth = store.getters['auth/isAuth']

  if (to.meta.requiresAuth && !isAuth || to.meta.requiresGuest && isAuth) {
    next({ name: 'home' })
  } else {
    next()
  }
})

export default router
