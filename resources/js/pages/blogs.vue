<script setup>
import BlogTable from '@/views/pages/tables/BlogTable.vue'
import { useRoute } from 'vue-router'

const route = useRoute()
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Blog list">
        <BlogTable />
      </VCard>
    </VCol>
  </VRow>
</template>
