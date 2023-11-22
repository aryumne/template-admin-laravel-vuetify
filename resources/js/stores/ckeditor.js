import { defineStore } from 'pinia'
import { ref } from 'vue'

export default defineStore('ckeditor', () => {
  let ckEditorInstance = ref(null)

  function clearCkeditor() {
    ckEditorInstance.value.setData('')
  }

  return { ckEditorInstance, clearCkeditor }
})
