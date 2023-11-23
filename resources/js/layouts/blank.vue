<script setup>
import { snackbarStore } from '@/stores'
</script>

<template>
  <div class="layout-wrapper layout-blank">
    <RouterView />
    <VSnackbar
      v-model="snackbarStore.isShow"
      multi-line
    >
      {{ snackbarStore.msg }}
      <template #actions>
        <VBtn @click="snackbarStore.toggle">
          Close
        </VBtn>
      </template>
    </VSnackbar>
  </div>
</template>

<style>
.layout-wrapper.layout-blank {
  flex-direction: column;
}
</style>
