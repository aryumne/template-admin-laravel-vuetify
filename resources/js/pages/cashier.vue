<script setup>
import CheckoutCard from "@/views/cashiers/CheckoutCard.vue"
import ScannerCard from "@/views/cashiers/ScannerCard.vue"
</script>

<template>
  <div>
    <p class="text-2xl mb-6">
      Kasir
    </p>
    <VRow>
      <VCol
        cols="12"
        lg="4"
        md="5"
        order-sm="first"
        order-md="last"
      >
        <ScannerCard />
      </VCol>
      <VCol
        cols="12"
        lg="8"
        md="7"
        order-sm="last"
        order-md="first"
      >
        <CheckoutCard />
      </VCol>
    </VRow>
  </div>
</template>
