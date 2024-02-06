<template>
  <VContainer>
    <VAutocomplete
      v-model:search="search"
      :loading="searchLoading"
      :items="searchResult"
      item-title="name"
      :custom-filter="customFilter"
      item-value="barcode"
      auto-select-first
      clear-on-select
      multiple
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
  </VContainer>
</template>

<script setup>
import { productService } from '@/services'
import { snackbarStore } from '@/stores'
import { ref } from 'vue'

const emitEvents = defineEmits(['getSelected'])
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
  if (val) emitEvents("getSelected", val)
  searchResult.value = []
  search.value = ""
}

const handlePaste = () => {
  setTimeout(() => 
    clicked(searchResult.value[0]?.barcode)
  , 200)
}
</script>
