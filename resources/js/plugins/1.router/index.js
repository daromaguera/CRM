import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router/auto'

function recursiveLayouts(route) {
  if (route.children) {
    for (let i = 0; i < route.children.length; i++)
      route.children[i] = recursiveLayouts(route.children[i])

    return route
  }

  return setupLayouts([route])[0]
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to) {
    if (to.hash) return { el: to.hash, behavior: 'smooth', top: 60 }

    return { top: 0 }
  },
  extendRoutes: (pages) => [
    ...[...pages].map((route) => recursiveLayouts(route)),
    {
      path: '/reset-password/:token/:email',
      name: 'reset-password',
      component: () => import('@/pages/reset-password.vue'), // Adjust the path to your component
    },
  ],
})

// router.beforeEach(async (to) => {
//   // Use Pinia Store
//   const authUserStore = useAuthUserStore()
//   // Load if user is logged in
//   const isLoggedIn = await authUserStore.isAuthenticated()

//   // Redirect to appropriate page if accessing home route
//   // if (!isLoggedIn && to.path !== '/login') {
//   //   return { path: '/login' }
//   // }

//   // If not logged in, prevent access to system pages
//   // if (!isLoggedIn && to.meta.requiresAuth) {
//   //   // redirect the user to the login page
//   //   return { name: 'login' }
//   // }

//   // Check if the user is logged in
//   if (isLoggedIn) {
//     const { authUser } = authUserStore
//     const isUnverified = authUser && !authUser.email_verified_at
//     const isMissingRealtorLicense = authUser && !authUser.realtor_license_no

//     // Redirect to email confirmation if email is unverified
//     if (isUnverified && to.path !== '/register/email-confirmation') {
//       return { name: 'registeremail-confirmation' }
//     }

//     // Redirect to additional information form if realtor license is missing
//     if (isMissingRealtorLicense && to.path !== '/register/more-information') {
//       return { name: 'register-more-information' }
//     }

//     // Redirect logged-in users away from login or register pages
//     if (to.path === '/login' || to.path === '/register/account') {
//       return { name: 'root' }
//     }

//     // Load if not super admin
//     // if (!isSuperAdmin) {
//     //   if (authStore.authPages.length == 0) await authStore.getAuthPages(authStore.userRole)

//     //   // Check page that is going to if it is in role pages
//     //   const isAccessible = authStore.authPages.includes(to.path)

//     //   // Forbid access if not in role pages and if page is not default page
//     //   if (!isAccessible && !to.meta.isDefault) {
//     //     return { name: 'forbidden' }
//     //   }
//     // }
//   }
// })

export { router }

export default function (app) {
  app.use(router)
}
