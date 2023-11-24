import { defineStore } from 'pinia'
import { ref } from 'vue'

export default defineStore('ckeditor', () => {
  let ckEditorInstance = ref(null)

  function setCkeditor(val = '') {
    console.log(val)
    ckEditorInstance.value.setData(val)
  }

  return { ckEditorInstance, setCkeditor }
})
