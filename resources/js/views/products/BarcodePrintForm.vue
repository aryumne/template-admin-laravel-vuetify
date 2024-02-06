<script setup>
import { productService } from '@/services'
import { barcodeStore, snackbarStore } from "@/stores"
import { ref } from 'vue'

const search = ref('')
const searchLoading = ref(false)
const searchResult = ref([])

const customFilter = (_, queryText, item) => {
  const textOne = item.raw.name.toLowerCase()
  const textTwo = item.raw.barcode.toLowerCase()
  const searchText = queryText.toLowerCase()

  return textOne.indexOf(searchText) > -1 ||
          textTwo.indexOf(searchText) > -1
}

const searchProduct = async searchVal => {
  try {
    searchLoading.value = true
    
    if (searchVal) {

      const res = await productService.searchForOrder({ params: { search: searchVal.trim() } })

      searchResult.value = res?.data
    }
  } catch (e) {
    snackbarStore.setMsg(e.message)
  } finally {
    searchLoading.value = false
  }
}

const clicked = val => {
  if (val) barcodeStore.add(val)
  searchResult.value = []
  search.value = ""
}

const handlePaste = () => {
  setTimeout(() => 
    clicked(searchResult.value[0]?.barcode)
  , 200)
}

onUnmounted(() => {
  barcodeStore.resetProduct()
})
</script>

<template>
  <VCard>
    <VCardItem>
      <VCardTitle class="mb-4">
        Cetak Barcode Obat
      </VCardTitle>
      <VAutocomplete
        v-model:search="search"
        :loading="searchLoading"
        :items="searchResult"
        item-title="name"
        :custom-filter="customFilter"
        item-value="barcode"
        placeholder="Ketik nama obat"
        auto-select-first
        clear-on-select
        clearable
        @update:search="searchProduct"
        @update:model-value="clicked"
        @paste="handlePaste"
      >
        <template #item="{ props, item }">
          <VListItem
            v-bind="props"
            :title="item.raw.name"
            :subtitle="item.raw.barcode"
          >
            <template #append>
              <div class="text-caption">
                {{ item.raw.total_item }}
              </div>
            </template>
          </VListItem>
        </template>
      </VAutocomplete>
   
      <VContainer>
        <VTable>
          <thead>
            <tr>
              <th class="text-body-2 font-weight-bold table-col-custom">
                #
              </th>
              <th>
                <div class="text-start">
                  Barcode
                </div>
              </th>
              <th>
                <div class="text-end">
                  Total
                </div>
              </th>
            </tr>
          </thead>
  
          <tbody>
            <tr
              v-for="item in barcodeStore.products"
              :key="item.barcode"
            >
              <td class="table-col-custom">
                <VBtn
                  density="compact"
                  variant="text"
                  color="error"
                  icon="mdi-window-close"
                  @click.prevent="barcodeStore.removeProduct(item.barcode)"
                />
              </td>
              <td class="text-start">
                {{ item.barcode }}
              </td>
              <td class="text-end">
                {{ item.rows * item.cols }}
              </td>
            </tr>
          </tbody>
        </VTable>
      </VContainer>
    </VCardItem>
  </VCard>
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

