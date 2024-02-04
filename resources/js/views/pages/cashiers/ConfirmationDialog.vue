<template>
  <VBtn
    color="primary"
    @click="dialog = true"
  >
    Bayar
  </VBtn>

  <VDialog
    v-model="dialog"
    width="auto"
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
            <tr>
              <td
                colspan="2"
                class="text-end font-weight-bold"
              >
                TOTAL
              </td>
              <td class="text-end font-weight-bold">
                Rp. {{ currencyFormat(props.amount) }}
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
          <!--
            <VBtn class="mx-2">
            Simpan & Cetak
            </VBtn> 
          -->
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
import { transactionService } from '@/services'
import { orderStore, snackbarStore } from "@/stores"
import { currencyFormat } from "@/utils"
import { ref } from 'vue'

const props = defineProps({
  amount: Number,
})

const dialog = ref(false)
const loading = ref(false)

const submit = async () => {
  try {
    loading.value = true

    const res = await transactionService.storeOrder({
      amount: props.amount,
      orders: orderStore.orders,
    })

    orderStore.resetOrder()
    snackbarStore.setMsg(res?.message)
  } catch (error) {
    snackbarStore.setMsg(error.message)
  } finally {
    dialog.value = false
    loading.value = false
  }
}
</script>


<style scoped>
tr > th, tr > td {
  padding: 8px 12px !important;
}
</style>
