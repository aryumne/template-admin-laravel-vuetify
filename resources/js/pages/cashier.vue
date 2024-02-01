<script setup>
import CheckoutCard from "@/views/pages/cashiers/CheckoutCard.vue"
import ScannerCard from "@/views/pages/cashiers/ScannerCard.vue"
</script>

<template>
  <div>
    <p class="text-2xl mb-6">
      Kasir
    </p>
    <VRow>
      <!-- 👉 Influencing The Influencer -->
      <VCol
        cols="12"
        lg="8"
        md="7"
      >
        <ScannerCard />
      </VCol>

      <!-- 👉 The Best Answers -->
      <VCol
        cols="12"
        lg="4"
        md="5"
      >
        <CheckoutCard />
      </VCol>
    </VRow>
  </div>
</template>
