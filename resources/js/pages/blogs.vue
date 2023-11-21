<script setup>
import BlogTable from '@/views/pages/tables/BlogTable.vue'
import { useRoute } from 'vue-router'

const route = useRoute()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <template #title>
          <VCardTitle class="text-h5 mb-2">
            Blog
          </VCardTitle>
          <RouterLink :to="{name:'createBlog'}">
            <VBtn
              type="button"
              class="mb-0"
              variant="tonal"
            >
              New blog
            </VBtn>
          </RouterLink>
        </template>
        <VCardText>
          <BlogTable />
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
