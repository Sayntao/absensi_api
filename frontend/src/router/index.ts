import { createRouter, createWebHistory } from 'vue-router'

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
      },
    },
    {
      path: '/profile',
      name: 'Profile',
      component: () => import('../views/Account/UserProfile.vue'),
      meta: {
        title: 'Profile',
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
      path: '/my-absen',
      name: 'My Absen',
      component: () => import('../views/HR/MyAbsen/index.vue'),
      meta: {
        title: 'My Absen',
      },
    },
    {
      path: '/employee-management',
      name: 'Employee Management',
      component: () => import('../views/HR/EmployeeManagement/index.vue'),
      meta: {
        title: 'Employee Management',
      },
    },
    {
      path: '/user-management',
      name: 'User Management',
      component: () => import('../views/Admin/UserManagement/index.vue'),
      meta: {
        title: 'User Management',
      },
    },
    {
      path: '/shift-management',
      name: 'Shift Management',
      component: () => import('../views/HR/ShiftManagement/index.vue'),
      meta: {
        title: 'Shift Management',
      },
    },
    {
      path: '/all-absen',
      name: 'All Absen',
      component: () => import('../views/HR/ReportAllAbsen/index.vue'),
      meta: {
        title: 'All Absen',
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

export default router

router.beforeEach((to, from, next) => {
  document.title = `Vue.js ${to.meta.title} | TailAdmin - Vue.js Tailwind CSS Dashboard Template`
  next()
})
