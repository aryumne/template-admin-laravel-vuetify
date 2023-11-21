<script setup>
import { blogService } from "@services"

const csrf_token = window.csrf_token

const routePrefix = '/filemanager'
const ckeditorRef = ref(null)

const data = ref({
  title: null,
  short_desc: null,
  thumb_url: null,
  desc: null,
  is_recomended: false,
  blog_type_id: null,
})

const blogTypes = ref([])

const send = async () => {
  try {
    const res = await blogService.store(data.value)

    console.log(res)
  } catch (e) {
    console.error(e.message)
  }
}

const openFileManager = () => {
  window.open(`${routePrefix}?type=file`, 'FileManager', 'width=900,height=600')
  window.SetUrl = items => {
    console.log(items[0].thumb_url)
    data.value.thumb_url = items[0].url
    console.log(data.value)
  }
}

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
  const editor = CKEDITOR.replace(ckeditorRef.value, {
    height: 300,
    filebrowserImageBrowseUrl: `${routePrefix}?type=Files`,
    filebrowserImageUploadUrl: `${routePrefix}/upload?type=Files&_token=${csrf_token}`,
    filebrowserBrowseUrl: `${routePrefix}?type=Files`,
    filebrowserUploadUrl: `${routePrefix}/upload?type=Files&_token=${csrf_token}`,
  })

  editor.on('change', () => {
    console.log(editor.getData())

    // Update the value in data.desc when CKEditor content changes
    data.value.desc = editor.getData()
  })
}

onMounted(async () => {
  const res = await blogService.getBlogTypes()

  blogTypes.value = res.data

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
  <div>
    <VRow>
      <VCol
        cols="12"
        md="8"
      >
        <!-- 👉 Multiple Column -->
        <VCard title="New Blog">
          <VCardText>
            <VForm @submit.prevent="send">
              <VRow>
                <VCol cols="12">
                  <VTextField
                    v-model="data.title"
                    label="Title"
                    placeholder="title"
                  />
                </VCol>
                <VCol cols="12">
                  <VTextField
                    v-model="data.short_desc"
                    label="Short desc"
                    placeholder="short_desc"
                  />
                </VCol>
                <VCol cols="12">
                  <VBtn
                    block
                    variant="tonal"
                    color="grey-lighten-1"
                    @click.prevent="openFileManager"
                  >
                    Choose thumbnail
                  </VBtn>
                  <VCard v-if="data.thumb_url !== null">
                    <VImg
                      :src="data.thumb_url"
                      max-height="250"
                      cover
                      class="bg-grey-lighten-2 mt-3"
                    />
                    <VCardTitle class="text-h6">
                      max-height with cover
                    </VCardTitle>
                  </VCard>
                </VCol>
                <VCol cols="12">
                  <VSelect
                    v-model="data.blog_type_id"
                    label="Select"
                    :items="blogTypes"
                    item-title="bt_name"
                    item-value="id"
                    variant="solo-inverted"
                  />
                </VCol>
                <VCol cols="12">
                  <VTextarea
                    ref="ckeditorRef"
                    label="Label"
                  />
                  <div style="width: 100%; height: 500px; overflow: hidden; border: none;">
                    <textarea
                      ref="ckeditorRef"
                      class="form-control"
                    />
                  </div> 
                </VCol>

                <VCol
                  cols="12"
                  class="d-flex gap-4"
                >
                  <VBtn type="submit">
                    Submit
                  </VBtn>

                  <VBtn
                    type="reset"
                    color="secondary"
                    variant="tonal"
                  >
                    Reset
                  </VBtn>
                </VCol>
              </VRow>
            </VForm>
            
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
