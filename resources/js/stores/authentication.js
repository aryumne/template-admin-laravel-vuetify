import { authService } from '@services'
import { hashing } from '@utils'
import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'

export default defineStore(
  'authentication',
  () => {
    const router = useRouter()
    const authenticated = ref(false)
    const user = ref({})

    const getUser = computed(() => {
      if (Object.keys(user.value).length === 0) {
        const userFromLS = localStorage.getItem('_USR')
        if (userFromLS !== undefined && userFromLS !== null) {
          user.value = JSON.parse(hashing.Decode(userFromLS))
        }
      }
      
      return user.value
    })

    async function signin() {
      try {
        const res = await authService.getUser()
        if (res.data !== null || res.data !== undefined) {
          authenticated.value = true
          user.value = res.data
          localStorage.setItem('_USR', hashing.Encode(JSON.stringify(user.value)))
          router.push({ name: 'dashboard', replace: true })
        }
      } catch (error) {
        signOut()
      }
    }

    function signOut() {
      // const alertStore = useAlertStore()

      // alertStore.setAlert({ isShow: true, type: 'warning', msg: 'Yau have been logout' })
      authenticated.value = false
      user.value = {}
      localStorage.clear()
      router.push({ name: 'login' })
    }
    
    return { authenticated, getUser, signin, signOut }
  },
  {
    persist: {
      paths: ['authenticated', 'user.value'],
    },
  },
)
