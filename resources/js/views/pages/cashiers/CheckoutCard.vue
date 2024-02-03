<template>
  <VCard>
    <VCardItem>
      <VCardTitle>
        <div class="text-h6">
          Barcode Scanner
        </div>
      </VCardTitle>
      <VDivider />
      
      <StreamBarcodeReader @decode="onDecode" />
    </VCardItem>
  </VCard>
</template>

<script setup>
import { orderStore } from "@/stores"
import { ref } from "vue"
import { StreamBarcodeReader } from "vue-barcode-reader"

const timer = ref( null)

const onDecode = res => {
  if (timer.value) {
    // So we clear and null it so it doesn't contact the api
    clearTimeout(timer.value)
    timer.value = null
  }

  timer.value = setTimeout(() => {
    if (res) {
      orderStore.add(res)
    }
  }, 1000)
}
</script>
