<script setup>
import { barcodeStore } from "@/stores"
import VueBarcode from '@chenfengyuan/vue-barcode'
import { ref } from "vue"

const printLoading = ref(true)

const printObj = ref({
  id: "printme",
  popTitle: 'Cetak Barcode',
  extraHead: '<meta http-equiv="Content-Language"content="zh-cn"/>',
  beforeOpenCallback (vue) {
    printLoading.value = true
    console.log('error before open')
  },
  openCallback (vue) {
    printLoading.value = false
    console.log('callback')
  },
  closeCallback (vue) {
    console.log('error')
  },
})
</script>

<template>
  <VContainer>
    <VBtn
      v-print="printObj"
      color="secondary"
    >
      <VIcon icon="mdi-printer" />
      Cetak
    </VBtn>
  </VContainer>
  <VSheet
    id="printme"
    :height="auto"
    :width="595.44"
    class="pa-4 text-center mx-auto"
  >
    <VRow>
      <VCol
        v-for="item in barcodeStore.products"
        :key="item.barcode"
        cols="12"
      >
        <VRow
          v-for="row in item.rows"
          :key="row"
        >
          <VCol
            v-for="col in item.cols"
            :key="col"
            cols="3"
          >
            <VueBarcode
              :value="item.barcode"
              tag="img"
              height="75"
            />
          </VCol>
        </VRow>
      </VCol>
    </VRow>
  </VSheet>
</template>


<style scoped>
.custom-width {
  padding: 0;
  max-inline-size: 100px;
}

.table-col-custom {
  max-width: 50px !important;
  padding-right: 0 !important;
  padding-left: 0 !important;
}
</style>

