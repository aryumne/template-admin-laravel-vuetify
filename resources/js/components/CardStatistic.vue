<script setup>
const props = defineProps({
  title: String,
  icon: String,
  counter: String,
  color: String,
})
</script>

<template>
  <VCard>
    <VCardText class="d-flex align-center pb-4">
      <p class="mb-1">
        {{ props.title }}
      </p>
              
      <VSpacer />
      <VAvatar
        rounded
        variant="tonal"
        :color="props.color"
      >
        <VIcon :icon="props.icon" />
      </VAvatar>
    </VCardText>


    <VCardText>
      <h4 class="text-h4 mb-3">
        {{ props.counter }}
      </h4>
    </VCardText>
  </VCard>
</template>
