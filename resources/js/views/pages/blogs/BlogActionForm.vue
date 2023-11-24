<script setup>
import Ckeditor from "@/components/Ckeditor.vue"
import { blogService } from "@services"
import { ckeditorStore, snackbarStore } from '@stores'

const props = defineProps({
  isNew: {
    type: Boolean,
    default: true,
  },
  blogId: {
    type: String,
    required: false,
  },
})

const routePrefix = '/filemanager'
const blogTypes = ref([])

const data = ref({
  title: null,
  short_desc: null,
  thumb_url: null,
  desc: null,
  is_recomended: false,
  blog_type_id: null,
})

const syncToDesc = value => {
  data.value.desc = value
}

const resetForm = () => {
  data.value = {
    title: null,
    short_desc: null,
    thumb_url: null,
    is_recomended: false,
    blog_type_id: null,
  }
  if (props.isNew) {
    data.value.desc = null
    ckeditorStore.setCkeditor()
  }
}

const send = async () => {
  try {
    const res = props.isNew ? await blogService.storeBlog(data.value) : await blogService.updateBlog(props.blogId, data.value)

    resetForm()
    await getBlog()
    snackbarStore.setMsg(res.message)
  } catch (e) {
    snackbarStore.setMsg(e.message)
  }
}

const openFileManager = () => {
  window.open(`${routePrefix}?type=file`, 'FileManager', 'width=900,height=600')
  window.SetUrl = items => {
    data.value.thumb_url = items[0].url
  }
}

const loadDescToCkeditor = () => {
  ckeditorStore.setCkeditor(data.value.desc)
}

const getBlog = async () => {
  if (!props.isNew) {
    const blogRes = await blogService.getOneBlog(props.blogId)

    data.value.title = blogRes?.data.title
    data.value.short_desc = blogRes?.data.short_desc
    data.value.desc = blogRes?.data.desc
    data.value.thumb_url = blogRes?.data.thumb_url
    data.value.is_recomended = blogRes?.data.is_recomended
    data.value.blog_type_id = blogRes?.data.blog_type_id
  }
}

onMounted(async () => {
  try {
    const btRes = await blogService.getBlogTypes()
    
    blogTypes.value = btRes?.data
    await getBlog()
  } catch (error) {
    snackbarStore.setMsg(error.message)
  }
})
</script>

<template>
  <VForm @submit.prevent="send">
    <VRow>
      <VCol cols="12">
        <VBtn
          block
          variant="tonal"
          color="grey-lighten-1"
          @click.prevent="openFileManager"
        >
          upload thumbnail
        </VBtn>
        <VCard v-if="data.thumb_url !== null">
          <VImg
            :src="data.thumb_url"
            max-height="250"
            cover
            class="bg-grey-lighten-2 mt-3"
          />
        </VCard>
      </VCol>
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
        <VSelect
          v-model="data.blog_type_id"
          label="Select blog type"
          :items="blogTypes"
          item-title="bt_name"
          item-value="id"
          variant="outlined"
        />
      </VCol>
      <VCol cols="12">
        <Ckeditor
          @set-desc="syncToDesc"
          @load-desc="loadDescToCkeditor"
        />
      </VCol>

      <VCol
        cols="12"
        class="d-flex gap-4"
      >
        <VBtn type="submit">
          Save
        </VBtn>

        <RouterLink :to="{name:'blogs'}">
          <VBtn
            type="reset"
            color="secondary"
            variant="tonal"
          >
            Discard
          </VBtn>
        </RouterLink>
      </VCol>
    </VRow>
  </VForm>
</template>

