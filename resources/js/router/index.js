import useAuthenticationStore from '@stores/authentication'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      redirect: '/cashier',
      meta: {
        middleware: 'auth',
        title: 'Kasir',
      },
    },
    {
      path: '/cashier',
      component: () => import('../layouts/navbarOnly.vue'),
      meta: {
        middleware: 'auth',
      },
      children: [
        {
          path: '',
          name: 'cashier',
          component: () => import('../pages/cashier.vue'),
          meta: {
            title: 'Kasir',
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
          path: '',
          name: 'dashboard',
          component: () => import('../pages/dashboard.vue'),
          meta: {
            title: 'Dashboard SIRARM',
          },
        },
        {
          path: 'blogs',
          name: 'blogs',
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
          path: 'blog/:id',
          name: 'editBlog',
          component: () => import('../pages/blogs-edit.vue'),
          meta: {
            title: 'Edit Blog',
          },
        },
        {
          name: 'products',
          path: 'products',
          component: () => import('../pages/product.vue'),
          meta: {
            title: 'Daftar Obat',
          },
        },
        {
          path: 'purchases',
          name: 'purchases',
          component: () => import('../pages/purchase.vue'),
          meta: {
            title: 'Laporan Pembelian',
          },
        },
        {
          path: 'products-add-stock',
          name: 'productsAddStock',
          component: () => import('../pages/purchase-form.vue'),
          meta: {
            title: 'Form Pembelian',
          },
        },
        {
          path: 'barcode-print',
          name: 'barcodePrint',
          component: () => import('../pages/barcode-print.vue'),
          meta: {
            title: 'Form Cetak Barcode',
          },
        },
        {
          path: 'sales',
          name: 'sales',
          component: () => import('../pages/sale.vue'),
          meta: {
            title: 'Daftar Penjualan',
          },
        },
        {
          path: 'transactions',
          name: 'transactions',
          component: () => import('../pages/transaction.vue'),
          meta: {
            title: 'Daftar Transaksi',
          },
        },
        {
          path: 'transactions/:id',
          name: 'transactionsEdit',
          component: () => import('../pages/transaction-edit.vue'),
          meta: {
            title: 'Edit Transaksi',
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
            title: 'Login SIRARM',
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
