<template>
  <VCard>
    <VCardItem>
      <VRow class="mb-4">
        <VCol
          cols="12"
          md="6"
          class="d-flex pb-0 justify-start"
        >
          <VCardTitle class="text-h6 my-auto">
            {{ title }}
          </VCardTitle>
        </VCol>
        <VCol
          cols="12"
          md="6"
          class="d-flex pa-0 justify-md-end jusitfy-sm-start"
        >
          <slot name="button" />
        </VCol>
      </VRow>
      <VDivider class="mt-6 mb-3" />
      <slot name="content" />
    </VCardItem>
  </VCard>
</template>

<script setup>
defineProps({
  title: String,
})
</script>
