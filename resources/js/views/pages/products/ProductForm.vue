<template>
  <VDialog
    v-model="dialog"
    persistent
    width="1024"
  >
    <template #activator="{ props }">
      <VBtn
        color="primary"
        v-bind="props"
      >
        Tambah
      </VBtn>
    </template>
    <VCard>
      <VCardTitle class="mt-4">
        <span class="text-h6">Tambah Obat Baru</span>
      </VCardTitle>
      <VCardText>
        <VContainer>
          <VRow>
            <VCol cols="12">
              <div class="text-subtitle-2 text-medium-emphasis">
                Nama Obat
              </div>
              <VTextField
                v-model="data.name"
                type="text"
                placeholder="Komix Herbal"
                required
              />
            </VCol>
            
            <VCol
              cols="12"
              sm="6"
            >
              <div class="text-subtitle-2 text-medium-emphasis">
                Nomor Batch
              </div>
              <VTextField
                v-model="data.batch_number"
                type="text"
                placeholder="BS123S4"
                required
              />
            </VCol>
            
            <VCol
              cols="12"
              sm="6"
            >
              <div class="text-subtitle-2 text-medium-emphasis">
                Jenis obat*
              </div>
              <VAutocomplete
                v-model="data.variant"
                :items="['Tablet', 'Kapsul', 'Salep','Sirup']"
                placeholder="Pilih jenis Obat"
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
            >
              <div class="text-subtitle-2 text-medium-emphasis">
                Harga per Box*
              </div>
              <VTextField
                v-model="data.pack_price"
                type="number"
                prefix="Rp. "
                placeholder="50000"
                required
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
            >
              <div class="text-subtitle-2 text-medium-emphasis">
                Harga per Pcs*
              </div>
              <VTextField
                v-model="data.item_price"
                type="number"
                prefix="Rp. "
                placeholder="3500"
                required
              />
            </VCol>
          </VRow>
        </VContainer>
      </VCardText>
      <VCardActions>
        <VContainer class="d-flex justify-end">
          <VSpacer />
          <VBtn
            color="blue-darken-1"
            variant="text"
            @click="dialog = false"
          >
            Batal
          </VBtn>
          <VBtn
            color="primary"
            variant="tonal"
            @click="dialog = false"
          >
            Simpan
          </VBtn>
        </VContainer>
      </VCardActions>
    </VCard>
  </VDialog>
</template>

<script setup>
import { ref } from 'vue'

const dialog = ref(false)

const data = ref({
  name: '',
  batch_number: '',
  variant: '',
  pack_price: 0,
  item_price: 0,
})
</script>
