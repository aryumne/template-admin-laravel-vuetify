<template>
  <VCard>
    <VCardItem>
      <VContainer>
        <VAutocomplete
          v-model:search="search"
          :loading="searchLoading"
          :items="searchResult"
          item-title="name"
          :custom-filter="customFilter"
          item-value="barcode"
          auto-select-first
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
      <VDivider />
      <VTable>
        <thead>
          <tr>
            <th class="text-body-2 font-weight-bold table-col-custom">
              #
            </th>
            <th>
              <div class="text-start">
                Obat
              </div>
            </th>
            <th>
              satuan
            </th>
            <th>
              jumlah
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
            v-for="item in orderStore.orders"
            :key="item.barcode"
          >
            <td class="table-col-custom">
              <VBtn
                density="compact"
                variant="text"
                color="error"
                icon="mdi-window-close"
                @click.prevent="orderStore.removeOrder(item.barcode)"
              />
            </td>
            <td class="text-start">
              {{ item.name }}
            </td>
            <td class="text-center">
              <VSelect
                density="compact"
                :value="item.type"
                class="custom-width mx-auto"
                :items="[OrderTypeEnum.PACK, OrderTypeEnum.PCS]"
                @update:model-value="updateType(item,$event)"
              />
            </td>
            <td class="text-center">
              <VTextField
                :value="item.quantity"
                density="compact"
                type="number"
                class="custom-width mx-auto"
                @update:model-value="updateQuantity(item, $event)"
              />
            </td>
            <td class="text-end">
              Rp. {{ currencyFormat(item.price * item.quantity) }}
            </td>
          </tr>
          <tr>
            <td
              colspan="4"
              class="text-center font-weight-bold bg-grey-100"
            >
              TOTAL
            </td>
            <td class="text-end font-weight-bold">
              Rp. {{ currencyFormat(totalOrders) }}
            </td>
          </tr>
        </tbody>
      </VTable>
      <VContainer class="d-flex justify-end">
        <ConfirmationDialog :amount="totalOrders" />
      </VContainer>
    </VCardItem>
  </VCard>
</template>

<script setup>
import { OrderTypeEnum } from "@/configs"
import { productService } from "@/services"
import { orderStore, snackbarStore } from "@/stores"
import { currencyFormat } from "@/utils"
import { ref } from "vue"
import ConfirmationDialog from "./ConfirmationDialog.vue"

const search =ref("")
const searchLoading = ref(false)
const searchResult = ref([])

function updateQuantity(item, value) {
  if (value > 0) {
    orderStore.changeQuantity(item.barcode, value)
  }
}

async function updateType(item, value) {
  await orderStore.changeType(item.barcode, value)
}

const totalOrders = computed(() => {
  return orderStore.orders.reduce((sum, item) => sum + item.quantity * item.price, 0)
})

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

const customFilter = (_, queryText, item) => {
  const textOne = item.raw.name.toLowerCase()
  const textTwo = item.raw.barcode.toLowerCase()
  const searchText = queryText.toLowerCase()

  return textOne.indexOf(searchText) > -1 ||
          textTwo.indexOf(searchText) > -1
}

const clicked = async val => {
  if(val) await orderStore.add(val)
  searchResult.value = []
  search.value = ""
}

const handlePaste = () => {
  setTimeout(() => 
    clicked(searchResult.value[0]?.barcode)
  , 200)
}
</script>

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

