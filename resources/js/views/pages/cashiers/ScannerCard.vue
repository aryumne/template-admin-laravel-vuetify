<template>
  <VCard>
    <VCardItem>
      <VContainer>
        <VAutocomplete
          v-model:search="search"
          :loading="searchLoading"
          :items="searchResult"
          item-title="name"
          item-value="barcode"
          clear-on-select
          clearable
          @update:search="searchProduct"
          @update:model-value="clicked"
          @update:focused="isFocused"
        />
      </VContainer>
      <VDivider />
      <VTable>
        <thead>
          <tr>
            <th class="text-uppercase text-start">
              Obat
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
            <td class="text-start">
              {{ item.name }}
            </td>
            <td class="text-center">
              <VSelect
                density="compact"
                :value="item.type"
                class="custom-width mx-auto"
                :items="['pack', 'pcs']"
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
              colspan="3"
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
        <VBtn color="success">
          Simpan
        </VBtn>
      </VContainer>
    </VCardItem>
  </VCard>
</template>

<script setup>
import { productService } from "@/services"
import { orderStore } from "@/stores"
import { currencyFormat } from "@/utils"
import { ref } from "vue"

const search =ref("")
const searchLoading = ref(false)
const searchResult = ref([])

function updateQuantity(item, value) {
  orderStore.changeQuantity(item.id, value)
}

async function updateType(item, value) {
  await orderStore.changeType(item.id, value)
}

const totalOrders = computed(() => {
  return orderStore.orders.reduce((sum, item) => sum + item.quantity * item.price, 0)
})

const searchProduct = async searchVal => {
  try {
    searchLoading.value = true
    
    if (searchVal !== "") {

      const res = await productService.searchForOrder({ params: { search: searchVal.trim() } })

      searchResult.value = res.data
    }
  } catch (e) {
    console.error(e)
  } finally {
    searchLoading.value = false
  }
}

const clicked = async val => {
  await orderStore.add(val)
}

const isFocused = val => {
  search.value = ""
}
</script>

<style scoped>
.custom-width {
  padding: 0;
  max-inline-size: 100px;
}
</style>
