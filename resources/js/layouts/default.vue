<script setup>
import { snackbarStore } from '@/stores'
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'
</script>

<template>
  <DefaultLayoutWithVerticalNav>
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
  </DefaultLayoutWithVerticalNav>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
