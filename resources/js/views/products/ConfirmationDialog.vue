<!-- eslint-disable vue/no-restricted-class -->
<template>
  <VBtn
    color="primary"
    @click="openDialog"
  >
    Simpan
  </VBtn>

  <VDialog
    v-model="dialog"
    width="500"
  >
    <VCard>
      <VCardItem>
        <VCardTitle>
          <div class="text-body-1 font-weight-bold">
            Detail pembelian
          </div>
        </VCardTitle>
        <VTable>
          <thead>
            <tr>
              <th>
                <div class="text-start">
                  Obat
                </div>
              </th>
              <th>
                <div class="text-end">
                  Jml
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
              v-for="item in orderStore.orders"
              :key="item.barcode"
            >
              <td class="text-start">
                {{ item.name }}
              </td>
              <td class="text-end">
                {{ item.quantity + ' '+ item.type }} 
              </td>
              <td class="text-end">
                Rp. {{ currencyFormat(item.price * item.quantity) }}
              </td>
            </tr>
          </tbody>
        </VTable>
        <VContainer class="d-flex justify-end">
          <VBtn
            variant="text"
            color="dark"
            @click="dialog = false"
          >
            Batal
          </VBtn>         
          <VBtn
            variant="tonal"
            @click.prevent="submit"
          >
            Simpan
          </VBtn>
        </VContainer>
      </VCardItem>
    </VCard>
  </VDialog>
</template>


<script setup>
import { productService } from '@/services'
import { orderStore, snackbarStore } from "@/stores"
import { currencyFormat } from "@/utils"
import { ref } from 'vue'

const props = defineProps({
  amount: Number,
})

const dialog = ref(false)
const loading = ref(false)

const openDialog = () => {
  if (orderStore.orders.length < 1) return snackbarStore.setMsg("Daftar pembelian masih kosong!")
  else return  dialog.value = true
}

const submit = async () => {
  try {
    loading.value = true

    const res = await productService.storePurchases({
      stocks: orderStore.orders,
    })

    orderStore.resetOrder()
    snackbarStore.setMsg(res?.message)
    dialog.value = false
  } catch (error) {
    snackbarStore.setMsg(error.message)
  } finally {
    loading.value = false
  }
}
</script>


<style scoped>
tr > th, tr > td {
  padding: 8px 12px !important;
}

.custom-width {
  padding: 0;
  max-inline-size: 100px;
}
</style>
