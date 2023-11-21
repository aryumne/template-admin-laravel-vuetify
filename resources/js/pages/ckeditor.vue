<script setup>
const csrf_token = window.csrf_token

const loadScript = src => {
  return new Promise((resolve, reject) => {
    const script = document.createElement('script')

    script.src = src
    script.onload = resolve
    script.onerror = reject
    document.head.appendChild(script)
  })
}

onMounted(async () => {
  // Load jQuery
  await loadScript('https://code.jquery.com/jquery-3.2.1.min.js')

  // Load Popper.js
  await loadScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js')

  // Load Bootstrap
  await loadScript('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js')

  // CKEditor initialization
  const route_prefix = "/filemanager"
  
  // Load CKEditor scripts
  await loadScript('//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/ckeditor.js')
  await loadScript('//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/adapters/jquery.js')

  // CKEditor initialization
  $('textarea[name=ce]').ckeditor({
    height: 300,
    filebrowserImageBrowseUrl: `${route_prefix}?type=Files`,
    filebrowserImageUploadUrl: `${route_prefix}/upload?type=Files&_token=${csrf_token}`, // Make sure csrf_token is defined
    filebrowserBrowseUrl: `${route_prefix}?type=Files`,
    filebrowserUploadUrl: `${route_prefix}/upload?type=Files&_token=${csrf_token}`, // Make sure csrf_token is defined
  })
})

const openDialog = () => window.open('/filemanager?type=Images&CKEditor=ce&CKEditorFuncNum=92&langCode=en')
</script>

<template>
  <div>
    <VRow>
      <VCol cols="12">
        <!-- 👉 Multiple Column -->
        <VCard title="Multiple Column">
          <VCardText>
            <VBtn @click="openDialog">
              Open filemanager
            </VBtn>
            <div style="width: 100%; height: 500px; overflow: hidden; border: none;">
              <textarea
                name="ce"
                class="form-control"
              />
            </div>
            <iframe
              src="/filemanager"
              style="width: 100%; height: 500px; overflow: hidden; border: none;"
            />
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>
