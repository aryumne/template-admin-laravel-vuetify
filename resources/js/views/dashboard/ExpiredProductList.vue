<script setup>
import { OrderTypeEnum } from '@/configs'

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
            Obat kedaluarsa dalam 3 Bulan kedepan
          </VCardTitle>
          <VCardSubtitle>Total {{ total }} obat</VCardSubtitle>
        </div>
        <VSpacer />
        <VAvatar
          rounded
          variant="tonal"
          color="error"
        >
          <VIcon icon="mdi-alert" />
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
            {{ order.pack_stok }} {{ OrderTypeEnum.PACK }}
          </VListItemSubtitle>

          <template #append>
            <span>{{ order.total_item }} {{ OrderTypeEnum.PCS }}</span>
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
