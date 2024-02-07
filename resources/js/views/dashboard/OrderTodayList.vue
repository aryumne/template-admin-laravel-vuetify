<script setup>
import { currencyFormat } from '@/utils'

defineProps({
  total: Number,
  data: Array,
})
</script>

<template>
  <VCard>
    <VCardItem class="pb-3">
      <div class="d-flex align-center">
        <div>
          <VCardTitle class="mb-1">
            Transaksi Hari ini
          </VCardTitle>
          <VCardSubtitle>Total Rp. {{ currencyFormat(total) }}</VCardSubtitle>
        </div>
        <VSpacer />
        <VAvatar
          rounded
          variant="tonal"
          color="info"
        >
          <VIcon icon="mdi-tag-check-outline" />
        </VAvatar>
      </div>
    </VCardItem>

    <VCardText>
      <VList
        class="card-list mt-7"
        height="350"
      >
        <VListItem
          v-for="(order, index) in data"
          :key="index"
        >
          <template #prepend>
            <div class="text-h6 pe-6">
              {{ index + 1 }}.
            </div>
          </template>

          <VListItemTitle class="mb-1">
            {{ order.name }}
          </VListItemTitle>
          <VListItemSubtitle class="text-disabled">
            {{ order.quantity }} {{ order.type }}
          </VListItemSubtitle>

          <template #append>
            <span>Rp.{{ currencyFormat(order.total_price) }},-</span>
          </template>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 21px;
}
</style>
