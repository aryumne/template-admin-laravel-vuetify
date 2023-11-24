import { defineStore } from 'pinia'
import { ref } from 'vue'

export default defineStore('snackbar', () => {
  const isShow = ref(false)
  const msg = ref("Default message")

  function toggle() {
    isShow.value = !isShow.value
  }
    
  function setMsg(val) {
    msg.value = val
    isShow.value = true
  }

  return { isShow, toggle, msg, setMsg }
})
