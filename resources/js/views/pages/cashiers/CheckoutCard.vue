<template>
  <VCard>
    <VCardItem>
      <VCardTitle>
        <div class="text-h6">
          Barcode Scanner
        </div>
      </VCardTitle>
      <VDivider />
      <StreamBarcodeReader
        @decode="onDecode"
        @loaded="onLoaded"
      />
    </VCardItem>
  </VCard>
</template>

<script setup>
import { StreamBarcodeReader } from "vue-barcode-reader"


const onDecode = res => {
  alert('Kode : '+res)
}

const onLoaded = res => {
  console.log(res)
}
</script>
