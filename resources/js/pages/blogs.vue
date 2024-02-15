<script setup>
import BlogTable from '@/views/blogs/BlogTable.vue'
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <template #title>
          <div class="d-flex justify-space-between">
            <VCardTitle class="text-h5 mb-2">
              Blog
            </VCardTitle>
            <RouterLink :to="{name:'createBlog'}">
              <VBtn
                prepend-icon="mdi-plus"
                type="button"
                class="mb-0"
                variant="tonal"
              >
                New
              </VBtn>
            </RouterLink>
          </div>
        </template>
        <VCardText>
          <BlogTable />
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
