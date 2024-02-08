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
            List stok obat menipis
          </VCardTitle>
          <VCardSubtitle>Total {{ total }} obat</VCardSubtitle>
        </div>
        <VSpacer />
        <VAvatar
          rounded
          variant="tonal"
          color="warning"
        >
          <VIcon icon="mdi-bell-alert" />
        </VAvatar>
      </div>
    </VCardItem>

    <VCardText>
      <VList
        class="card-list mt-7"
        max-height="375"
      >
        <VListItem
          v-for="(order, index) in data"
          :key="index"
        >
          <template #prepend>
            <p
              class="text-subtitle-1 me-2"
              style="width: 2rem;"
            >
              {{ index + 1 }}.
            </p>
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
