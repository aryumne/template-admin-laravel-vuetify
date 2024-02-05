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
      <VForm @submit.prevent="save">
        <VCardItem class="px-8 mb-4 ">
          <VRow>
            <VCol
              cols="12"
              sm="6"
            >
              <div class="text-subtitle-2 text-medium-emphasis">
                Nama Obat
              </div>
              <VTextField
                v-model="data.name"
                type="text"
                placeholder="Komix Herbal"
                :rules="rules.nameRules"
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
                v-model="data.product_type_id"
                :items="productTypes"
                item-title="name"
                item-value="id"
                placeholder="Pilih jenis Obat"
                :rules="rules.productTypeRules"
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
                Stok Pack*
              </div>
              <VTextField
                v-model="data.pack_stok"
                type="number"
                placeholder="50000"
                required
              />
            </VCol>
            <VCol
              cols="12"
              sm="6"
            >
              <div class="text-subtitle-2 text-medium-emphasis">
                Harga per Pack*
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
                Jumlah pcs per pack
              </div>
              <VTextField
                v-model="data.items_per_pack"
                type="number"
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
            <VCol
              cols="12"
              sm="6"
            >
              <div class="text-subtitle-2 text-medium-emphasis">
                Total Pcs
              </div>
              <VTextField
                v-model="data.total_item"
                type="number"
                placeholder="3500"
                required
              />
            </VCol>
          </VRow>
          <VRow>
            <VCol
              cols="12"
              class="d-flex justify-end"
            >
              <VBtn
                color="blue-darken-1"
                variant="text"
                @click="dialog = false"
              >
                Batal
              </VBtn>
              <VBtn
                type="submit"
                :loading="loading"
                color="primary"
                variant="tonal"
              >
                Simpan
              </VBtn>
            </VCol>
          </VRow>
        </VCardItem>
      </VForm>
    </VCard>
  </VDialog>
</template>

<script setup>
import { productService } from '@/services'
import { snackbarStore } from '@/stores'
import { onMounted, ref } from 'vue'

const dialog = ref(false)

const productTypes = ref([])
const loading= ref(false)

const data = ref({
  name: '',
  batch_number: '',
  product_type_id: '',
  pack_stok: 0,
  pack_price: 0,
  items_per_pack: 0,
  item_price: 0,
  total_item: 0,
})

const rules = ref({
  nameRules: [ value => {
    if (value) return true

    return 'Nama obat harus diisi!'
  }],
  productTypeRules: [ value => {
    if (value) return true

    return 'Jenis obat harus diisi!'
  }],
})

const resetForm = () => {
  data.value = {
    name: '',
    batch_number: '',
    product_type_id: '',
    pack_stok: 0,
    pack_price: 0,
    items_per_pack: 0,
    item_price: 0,
    total_item: 0,
  }
}


const save = async() => {
  try {
    loading.value = true

    const res = await productService.storeProduct(data.value)
    
    snackbarStore.setMsg(res?.message)
    resetForm()
    dialog.value = false
  } catch (e) {
    snackbarStore.setMsg(e.message)
  } finally {
    loading.value = false
  }
}

const getData = async() => {
  try {
    const res = await productService.getProductTypes()

    productTypes.value = res?.data
  } catch (e) {
    snackbarStore.setMsg(e.message)
  }
}

onMounted(getData)
</script>
