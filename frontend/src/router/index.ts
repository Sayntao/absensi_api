import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'Ecommerce',
      component: () => import('../views/Ecommerce.vue'),
      meta: {
        title: 'eCommerce Dashboard',
        requiresAuth: true,
      },
    },
    {
      path: '/profile',
      name: 'Profile',
      component: () => import('../views/Account/UserProfile.vue'),
      meta: {
        title: 'Profile',
        requiresAuth: true,
      },
    },

    {
      path: '/signin',
      name: 'Signin',
      component: () => import('../views/Auth/Signin.vue'),
      meta: {
        title: 'Signin',
      },
    },
    {
      path: '/signup',
      name: 'Signup',
      component: () => import('../views/Auth/Signup.vue'),
      meta: {
        title: 'Signup',
      },
    },

    {
      path: '/absen',
      name: 'Absen',
      component: () => import('../views/Absen/index.vue'),
      meta: {
        title: 'Absen',
        requiresAuth: true,
      },
    },

    {
      path: '/my-absen',
      name: 'My Absen',
      component: () => import('../views/MyAbsen/index.vue'),
      meta: {
        title: 'My Absen',
        requiresAuth: true,
      },
    },
    {
      path: '/employee-management',
      name: 'Employee Management',
      component: () => import('../views/HR/EmployeeManagement/index.vue'),
      meta: {
        title: 'Employee Management',
        requiresAuth: true,
      },
    },
    {
      path: '/user-management',
      name: 'User Management',
      component: () => import('../views/Admin/UserManagement/index.vue'),
      meta: {
        title: 'User Management',
        requiresAuth: true,
      },
    },
    {
      path: '/shift-management',
      name: 'Shift Management',
      component: () => import('../views/HR/ShiftManagement/index.vue'),
      meta: {
        title: 'Shift Management',
        requiresAuth: true,
      },
    },

    {
      path: '/all-absen',
      name: 'All Absen',
      component: () => import('../views/HR/ReportAllAbsen/index.vue'),
      meta: {
        title: 'All Absen',
        requiresAuth: true,
      },
    },
    {
      path: '/:pathMatch(.*)*',
      name: '404 Error',
      component: () => import('../views/Errors/FourZeroFour.vue'),
      meta: {
        title: '404 Error',
      },
    },
  ],
})

router.beforeEach((to, from, next) => {
  document.title = `Vue.js ${to.meta?.title || ''} | TailAdmin - Vue.js Tailwind CSS Dashboard Template`
  const authStore = useAuthStore()

  if (to.meta?.requiresAuth && !authStore.isAuthenticated()) {
    next({ path: '/signin', query: { redirect: to.fullPath } })
    return
  }

  if (!to.meta?.requiresAuth && authStore.isAuthenticated()) {
    if (to.name === 'Signin' || to.name === 'Signup') {
      next({ path: '/' })
      return
    }
  }

  next()
})

export default router
