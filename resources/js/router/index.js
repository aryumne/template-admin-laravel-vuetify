import useAuthenticationStore from '@stores/authentication'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      redirect: '/orders',
      meta: {
        middleware: 'auth',
        title: 'orders',
      },
    },
    {
      path: '/',
      component: () => import('../layouts/navbarOnly.vue'),
      meta: {
        middleware: 'auth',
      },
      children: [
        {
          path: 'orders',
          name: 'orders',
          component: () => import('../pages/orders.vue'),
          meta: {
            title: 'orders',
          },
        },
      ],
    },
    {
      path: '/admin',
      component: () => import('../layouts/default.vue'),
      meta: {
        middleware: 'auth',
      },
      children: [
        {
          name: 'dashboard',
          path: 'dashboard',
          component: () => import('../pages/dashboard.vue'),
          meta: {
            title: 'Dashboard',
          },
        },
        {
          name: 'products',
          path: 'products',
          component: () => import('../pages/products.vue'),
          meta: {
            title: 'Products',
          },
        },
        {
          name: 'blogs',
          path: 'blogs',
          component: () => import('../pages/blogs.vue'),
          meta: {
            title: 'Blogs',
          },
        },
        {
          name: 'createBlog',
          path: 'blog/create',
          component: () => import('../pages/blogs-create.vue'),
          meta: {
            title: 'Create Blog',
          },
        },
        {
          name: 'editBlog',
          path: 'blog/:id',
          component: () => import('../pages/blogs-edit.vue'),
          meta: {
            title: 'Edit Blog',
          },
        },
        {
          path: 'typography',
          component: () => import('../pages/typography.vue'),
          meta: {
            title: 'Typography',
          },
        },
        {
          path: 'icons',
          component: () => import('../pages/icons.vue'),
          meta: {
            title: 'Icons',
          },
        },
        {
          path: 'orders',
          component: () => import('../pages/orders.vue'),
          meta: {
            title: 'orders',
          },
        },
        {
          path: 'tables',
          component: () => import('../pages/tables.vue'),
          meta: {
            title: 'Tables',
          },
        },
        {
          path: 'form-layouts',
          component: () => import('../pages/form-layouts.vue'),
          meta: {
            title: 'Form',
          },
        },
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      meta: {
        middleware: 'guest',
      },
      children: [
        {
          name: 'login',
          path: 'login',
          component: () => import('../pages/login.vue'),
          meta: {
            title: 'Login Page',
          },
        },
        {
          path: 'register',
          component: () => import('../pages/register.vue'),
          meta: {
            title: 'Register Page',
          },
        },
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      children: [
        {
          path: '/:pathMatch(.*)*',
          component: () => import('../pages/[...all].vue'),
          meta: {
            title: 'Page not found!',
          },
        },
      ],
    },
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthenticationStore()

  document.title = to.meta.title
  if (to.meta.middleware == 'guest') {
    if (authStore.authenticated) {
      next({ name: 'dashboard' })
      
      return
    }
    next()
  } else {
    if (!authStore.authenticated) {
      next({ name: 'login' })
  
      return
    }
    next()
  }
})

export default router
