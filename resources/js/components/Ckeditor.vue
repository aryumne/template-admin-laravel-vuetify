<script setup>
import { ckeditorStore } from '@stores'

const emitEvents = defineEmits(['setCkeditor'])
const csrf_token = window.csrf_token
const routePrefix = '/filemanager'
const ckeditorRef = ref(null)

const loadScript = src => {
  return new Promise((resolve, reject) => {
    const script = document.createElement('script')

    script.src = src
    script.onload = resolve
    script.onerror = reject
    document.head.appendChild(script)
  })
}

const initializeCKEditor = () => {
  ckeditorStore.ckEditorInstance = CKEDITOR.replace(ckeditorRef.value, {
    height: 300,
    filebrowserImageBrowseUrl: `${routePrefix}?type=Files`,
    filebrowserImageUploadUrl: `${routePrefix}/upload?type=Files&_token=${csrf_token}`,
    filebrowserBrowseUrl: `${routePrefix}?type=Files`,
    filebrowserUploadUrl: `${routePrefix}/upload?type=Files&_token=${csrf_token}`,
  })

  ckeditorStore.ckEditorInstance.on('change', () => {
    // Update the value in data.desc when CKEditor content changes
    emitEvents('setCkeditor', ckeditorStore.ckEditorInstance.getData())
  })
}

onMounted(async () => {
  // Load jQuery
  await loadScript('https://code.jquery.com/jquery-3.2.1.min.js')

  // Load Popper.js
  await loadScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js')

  // Load Bootstrap
  await loadScript('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js')
  
  // Load CKEditor scripts
  await loadScript('//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/ckeditor.js')
  await loadScript('//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/adapters/jquery.js')

  // CKEditor initialization
  initializeCKEditor()
})
</script>

<template>
  <textarea
    ref="ckeditorRef"
    class="form-control"
  />
</template>
