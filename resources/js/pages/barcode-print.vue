<script setup>
import BarcodePrintForm from '@/views/products/BarcodePrintForm.vue'
import PreviewBarcodePrint from '@/views/products/PreviewBarcodePrint.vue'
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="4"
      lg="3"
    >
      <BarcodePrintForm />
    </VCol>
    <VCol
      cols="12"
      md="8"
      lg="9"
    >
      <PreviewBarcodePrint />
    </VCol>
  </VRow>
</template>
