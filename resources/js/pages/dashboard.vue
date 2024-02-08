<script setup>
import CardStatistic from '@/components/CardStatistic.vue'
import { dashboardService } from '@/services'
import { snackbarStore } from '@/stores'
import { currencyFormat } from '@/utils'
import ExpiredProductList from '@/views/dashboard/ExpiredProductList.vue'
import LowStockProductList from '@/views/dashboard/LowStockProductList.vue'
import OrderTodayList from '@/views/dashboard/OrderTodayList.vue'
import { onMounted, ref } from 'vue'

const data = ref({
  cardStatistic: {
    totalTransaction: 0,
    totalProduct: 0,
    totalTransactionThisMonth: 0,
    totalPurchasesThisMonth: 0,
  },
  orderTodayList: {
    total: 0,
    data: [],
  },
  lowStockProductList: {
    total: 0,
    data: [],
  },
  expiredProductList: {
    total: 0,
    data: [],
  },
})

onMounted(async () => {
  try {
    const res = await dashboardService.getAnalytics()

    data.value.cardStatistic.totalTransaction = res?.data.cardStatistic.totalTransaction
    data.value.cardStatistic.totalProduct = res?.data.cardStatistic.totalProduct
    data.value.cardStatistic.totalTransactionThisMonth = res?.data.cardStatistic.totalTransactionThisMonth
    data.value.cardStatistic.totalPurchasesThisMonth = res?.data.cardStatistic.totalPurchasesThisMonth

    data.value.orderTodayList.total = res?.data.orderTodayList.total
    data.value.orderTodayList.data = res?.data.orderTodayList.data

    data.value.lowStockProductList.total = res?.data.lowStockProductList.total
    data.value.lowStockProductList.data = res?.data.lowStockProductList.data

    data.value.expiredProductList.total = res?.data.expiredProductList.total
    data.value.expiredProductList.data = res?.data.expiredProductList.data
  } catch (error) {
    snackbarStore.setMsg(error.message)
  }
})
</script>

<template>
  <VRow>
    <!-- 👉 Congratulations -->
    <VCol
      cols="12"
      md="6"  
      order-md="first"
      order-sm="last"
    >
      <VRow>
        <VCol cols="12">
          <OrderTodayList
            :total="data.orderTodayList.total"
            :data="data.orderTodayList.data"
          />
        </VCol>
        <VCol cols="12">
          <LowStockProductList
            :total="data.lowStockProductList.total"
            :data="data.lowStockProductList.data"
          />
        </VCol>
      </VRow>
    </VCol>

    <VCol
      cols="12"
      md="6"
      order-md="last"
      order-sm="first"
    >
      <VRow>
        <!-- 👉 Profit -->
        <VCol
          cols="12"
          sm="6"
        >
          <CardStatistic
            title="Total Transaksi"
            icon="mdi-credit-card-outline"
            color="success"
            :counter="data.cardStatistic.totalTransaction"
          />
        </VCol>

        <VCol
          cols="12"
          sm="6"
        >
          <CardStatistic
            title="Total Obat"
            icon="mdi-pill-multiple"
            color="info"
            :counter="data.cardStatistic.totalProduct"
          />
        </VCol>
        <VCol
          cols="12"
          sm="6"
        >
          <CardStatistic
            title="Penjualan bulan ini"
            icon="mdi-finance"
            :counter="`Rp. ${currencyFormat(data.cardStatistic.totalTransactionThisMonth)}`"
            color="primary"
          />
        </VCol>
        <VCol
          cols="12"
          sm="6"
        >
          <CardStatistic
            title="Pembelian bulan ini"
            color="warning"
            icon="mdi-chart-box-plus-outline"
            :counter="`Rp. ${currencyFormat(data.cardStatistic.totalPurchasesThisMonth)}`"
          />
        </VCol>
        <VCol cols="12">
          <ExpiredProductList
            :total="data.expiredProductList.total"
            :data="data.expiredProductList.data"
          />
        </VCol>
      </VRow>
    </VCol>
  </VRow>
</template>


<style>
::-webkit-scrollbar {
  width: 5px;
}
</style>
