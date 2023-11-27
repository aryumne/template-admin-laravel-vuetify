<script setup>
import { blogService } from "@services"
import { snackbarStore } from '@stores'

const blogs = ref([])

const getData = async () => {
  try {
    const res = await blogService.getBlogsWithGrouping()

    blogs.value = res?.data
  } catch (e) {
    snackbarStore.setMsg(e.message)
  }
}

onMounted(getData)
</script>

<template>
  <VRow>
    <!-- 👉 Profit -->
    <VCol
      v-for="blog in blogs"
      :key="blog.blog_type_id"
      cols="6"
      md="4"
      lg="3"
    >
      <VCard>
        <VCardText>
          <p class="mb-4">
            {{ blog?.blog_type?.bt_name }}
          </p>
          <h5 class="text-h5 text-no-wrap mb-3">
            {{ blog?.total_blog }}
          </h5>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
