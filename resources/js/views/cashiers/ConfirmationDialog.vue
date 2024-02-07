<!-- eslint-disable vue/no-restricted-class -->
<template>
  <VBtn
    color="primary"
    @click="openDialog"
  >
    Bayar
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
        <VContainer class="px-2">
          <VRow align="center">
            <VCol cols="4">
              <div class="text-body-2 font-weight-bold">
                No. Resep :
              </div>
            </VCol>
            <VCol cols="8">
              <VTextField
                v-model="prescriptionNumber"
                variant="underlined"
                density="compact"
                type="text"
              />
            </VCol>
          </VRow>
        </VContainer>
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
              <td class="text-end font-weight-bold text-success">
                Rp. {{ currencyFormat(props.amount) }}
              </td>
            </tr>
            <tr>
              <td
                colspan="2"
                class="text-end font-weight-bold"
              >
                Tunai
              </td>
              <td class="text-end font-weight-bold">
                <VTextField
                  v-model="cashAmount"
                  variant="underlined"
                  density="compact"
                  type="number"
                  prefix="Rp. "
                  class="custom-width ml-auto"
                />
              </td>
            </tr>
            <tr>
              <td
                colspan="2"
                class="text-end font-weight-bold"
              >
                Kembali
              </td>
              <td
                class="text-end font-weight-bold"
                :class="{ 'text-error' : cashAmount < props.amount}"
              >
                Rp. {{ currencyFormat(returnAmount) }}
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
            class="mx-2"
            @click.prevent="submit(true)"
          >
            Simpan & Cetak
          </VBtn> 
         
          <VBtn
            variant="tonal"
            @click.prevent="submit(false)"
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
import { ref, watch } from 'vue'


const props = defineProps({
  amount: Number,
})

const dialog = ref(false)
const loading = ref(false)
const cashAmount = ref(0)
const returnAmount = ref(0)
const prescriptionNumber = ref('')


watch(cashAmount, (newVal, oldVal) => {
  let result = props.amount - newVal
  returnAmount.value  = Math.abs(result)
})


const openDialog = () => {
  if (orderStore.orders.length < 1) return snackbarStore.setMsg("Daftar pembelian masih kosong!")
  else return  dialog.value = true
}

const submit = async isPrint => {
  try {
    loading.value = true
    if(cashAmount.value < props.amount) throw new Error("Uang tunai tidak cukup!")

    const res = await transactionService.storeOrder({
      amount: props.amount,
      orders: orderStore.orders,
      cash_amount: cashAmount.value,
      return_amount: returnAmount.value,
      prescription_number: prescriptionNumber.value,
    })

    if (isPrint) {
      const url = await transactionService.downloadInvoice(res.data.id)

      // Open the PDF in a new tab
      window.open(url, '_blank')
    
    }

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
