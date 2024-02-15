<script setup>
import BlogActionForm from '@/views/blogs/BlogActionForm.vue'
</script>

<template>
  <div>
    <VRow justify="center">
      <VCol
        cols="12"
        md="10"
        lg="8"
      >
        <!-- 👉 Multiple Column -->
        <VCard title="New Blog">
          <VCardText>
            <BlogActionForm
              :is-new="false"
              :blog-id="$route.params.id"
            />
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>
