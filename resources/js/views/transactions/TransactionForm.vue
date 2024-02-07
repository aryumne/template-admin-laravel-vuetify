<template>
  <VCard>
    <VCardItem>
      <VCardTitle>
        Detail Transaksi {{ data.transaction_number }}
        <VAlert
          variant="tonal"
          type="warning"
          prominent
          density="compact"
          class="my-4 text-body-1"
        >
          Data ini sensitif terhadap data penjualan. Pastikan anda tidak mengubah jika tidak diperlukan!
        </VAlert>
      </VCardTitle>
      <VContainer>
        <VAutocomplete
          v-model:search="search"
          :loading="searchLoading"
          :items="searchResult"
          item-title="name"
          :custom-filter="customFilter"
          item-value="barcode"
          auto-select-first
          placeholder="Cari obat"
          @update:search="searchProduct"
          @update:model-value="clicked"
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
              v-for="item in data.orders"
              :key="item.barcode"
            >
              <td class="table-col-custom">
                <VBtn
                  density="compact"
                  variant="text"
                  color="error"
                  icon="mdi-window-close"
                  @click.prevent="removeOrder(item.barcode)"
                />
              </td>
              <td class="text-start">
                {{ item.name }}
              </td>
              <td class="text-center">
                <VSelect
                  v-if="item.canChange"
                  density="compact"
                  :value="item.type"
                  class="custom-width mx-auto"
                  :items="[OrderTypeEnum.PACK, OrderTypeEnum.PCS]"
                  @update:model-value="updateType(item,$event)"
                />
                <div
                  v-else
                  class="text-body-1"
                >
                  {{ item.type }}
                </div>
              </td>
              <td class="text-center">
                <VTextField
                  v-if="item.canChange"
                  :value="item.quantity"
                  density="compact"
                  type="number"
                  class="custom-width mx-auto"
                  @update:model-value="updateQuantity(item, $event)"
                />
                <div
                  v-else
                  class="text-body-1"
                >
                  {{ item.quantity }}
                </div>
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
            <tr>
              <td
                colspan="4"
                class="text-end font-weight-bold"
              >
                Tunai
              </td>
              <td class="text-end font-weight-bold">
                <VTextField
                  v-model="data.cash_amount"
                  variant="underlined"
                  density="compact"
                  type="number"
                  prefix="Rp. "
                  class="custom-tf"
                />
              </td>
            </tr>
            <tr>
              <td
                colspan="4"
                class="text-end font-weight-bold"
              >
                Kembali
              </td>
              <td
                class="text-end font-weight-bold"
                :class="{ 'text-error' : data.cash_amount < data.amount}"
              >
                Rp. {{ currencyFormat(data.return_amount) }}
              </td>
            </tr>
            <tr>
              <td
                colspan="4"
                class="text-end font-weight-bold"
              >
                Nomor Resep
              </td>
              <td class="text-end font-weight-bold">
                <VTextField
                  v-model="data.prescription_number"
                  variant="underlined"
                  density="compact"
                  type="text"
                  class="custom-tf"
                />
              </td>
            </tr>
          </tbody>
        </VTable>
      </VContainer>
      <VContainer class="d-flex justify-end">
        <ConfirmationDialog
          :id="$route.params.id"
          :data="data"
          @get-data="getData"
        />
      </VContainer>
    </VCardItem>
  </VCard>
</template>

<script setup>
import { OrderTypeEnum } from "@/configs"
import { productService, transactionService } from "@/services"
import { snackbarStore } from "@/stores"
import { currencyFormat } from "@/utils"
import { onMounted, ref } from "vue"
import { useRoute } from "vue-router"
import ConfirmationDialog from "./ConfirmationDialog.vue"


const routeInfo = useRoute()
const search =ref("")
const searchLoading = ref(false)
const searchResult = ref([])

const data = ref({
  prescription_number: '',
  transaction_number: '',
  amount: 0,
  cash_amount: 0,
  return_amount: 0,
  orders: [],
})

const oldOrders = ref([])


// Load data
const getData = async () => {
  try {
    const res = await transactionService.getOneTransaction(routeInfo.params.id)

    data.value.transaction_number = res?.data?.transaction_number
    data.value.amount = res?.data?.amount
    data.value.cash_amount = res?.data?.cash_amount
    data.value.return_amount = res?.data?.return_amount
    data.value.prescription_number = res?.data?.prescription_number

    data.value.orders  = res?.data.orders.map(item => ({
      name: item.name,
      barcode: item.barcode,
      type: item.type,
      quantity: item.quantity,
      price: item.price,
      total_price: item.total_price,
      transaction_number: item.transaction_number,
      canChange: false,
    }))

    oldOrders.value = res?.data.orders.map(item => ({
      name: item.name,
      barcode: item.barcode,
      type: item.type,
      quantity: item.quantity,
      price: item.price,
      total_price: item.total_price,
      transaction_number: item.transaction_number,
      canChange: false,
    }))
  } catch (error) {
    snackbarStore.setMsg(error.message)
  }
}

onMounted(getData)


// watch perubahan data orders
watch(data, (newVal, oldVal) => {
  let result = newVal.cash_amount - newVal.amount
  data.value.return_amount  = Math.abs(result)
}, { deep: true })


// update quantity pesanan
const updateQuantity = (item, value) => {
  if (value > 0) {
    let order = data.value.orders.find(order => order.barcode === item.barcode)
    order.quantity = value
    order.total_price = value * order.price   
  }
}

// update type pembelian
const updateType = async (item, type) => {
  try {
    const res = await productService.searchByBarcode(item.barcode)
    let order = data.value.orders.find(order => order.barcode === item.barcode)
    order.type = type
    if (order.type === OrderTypeEnum.PACK) {
      order.price = res?.data.pack_price
      order.total_price = res?.data.pack_price + order.quantity
    } else {
      order.price = res?.data.item_price
      order.total_price = res?.data.item_price + order.quantity
    }
  } catch (error) {
    snackbarStore.setMsg(error.message)
  }
}

// hapus order
const removeOrder = barcode => {
  const indexOfOrder = data.value.orders.findIndex(order => order.barcode === barcode)
  if (indexOfOrder !== -1) data.value.orders.splice(indexOfOrder, 1)
}

// total orderan
const totalOrders = computed(() => {
  data.value.amount = data.value.orders.reduce((sum, item) => sum + item.quantity * item.price, 0)
  
  return data.value.amount
})

// pencarian product
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

// custom filter
const customFilter = (_, queryText, item) => {
  const textOne = item.raw.name.toLowerCase()
  const textTwo = item.raw.barcode.toLowerCase()
  const searchText = queryText.toLowerCase()

  return textOne.indexOf(searchText) > -1 ||
          textTwo.indexOf(searchText) > -1
}

// trigger ketika option diklik
const clicked = async barcode => {
  try {
    if (barcode != null || barcode != undefined) {
      let existingOrder = data.value.orders.find(order => order.barcode === barcode)
      let existingOldOrder = oldOrders.value.find(order => order.barcode === barcode)
      if (existingOldOrder) throw new Error("Hanya dapat menukar, tidak dapat menambah obat yang sama!")
      if (existingOrder === undefined && existingOldOrder === undefined) {
        const res = await productService.searchByBarcode(barcode)
        
        const newOrder = {
          name: res?.data.name,
          barcode: res?.data.barcode,
          type: OrderTypeEnum.PCS,
          quantity: 1,
          price: res?.data.item_price,
          total_price: res?.data.item_price,
          transaction_number: data.value.transaction_number,
          canChange: true,
        }

        data.value.orders.push(newOrder)
      } 
    }
  } catch (error) {
    snackbarStore.setMsg(error.message)
  } finally {
    searchResult.value = []
    search.value = ""
  }
}
</script>

<style scoped>
.custom-width {
  padding: 0;
  max-inline-size: 100px;
}
.custom-tf {
    max-width: 200px !important;
    margin-left: auto;
}

.table-col-custom {
  max-width: 50px !important;
  padding-right: 0 !important;
  padding-left: 0 !important;
}
</style>

