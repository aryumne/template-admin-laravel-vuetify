<template>
  <VCard>
    <VCardTitle class="mt-4">
      <span class="text-h6">Edit {{ data.name }}</span>
    </VCardTitle>
    <VForm @submit.prevent="save">
      <VCardItem class="px-8 mb-4 ">
        <VRow>
          <VCol
            cols="12"
            sm="6"
          >
            <div class="text-subtitle-2 text-medium-emphasis">
              Nama Obat*
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
              Tanggal Kedaluarsa*
            </div>
            <VTextField
              v-model="data.expired_date"
              type="date"
              :rules="rules.expiredDateRules"
              required
            />
          </VCol>
          <VCol
            cols="12"
            sm="6"
          >
            <div class="text-subtitle-2 text-medium-emphasis">
              Stok Box*
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
              Jumlah Pcs per Box*
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
              Total Pcs*
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
              @click="$emit('closeDialog')"
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
</template>

<script setup>
import { productService } from '@/services'
import { snackbarStore } from '@/stores'
import { onMounted, ref } from 'vue'


const props = defineProps({
  dialog: Boolean,
  uuid: String,
})

const emitEvents = defineEmits(['closeDialog'])

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
  expired_date: null,
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
  expiredDateRules: [ value => {
    if (value) return true

    return 'Tanggal kedaluarsa harus diisi!'
  }],
})

const save = async() => {
  try {
    loading.value = true

    const res = await productService.updateProduct(props.uuid, data.value)
    
    snackbarStore.setMsg(res?.message)
    emitEvents('closeDialog')
  } catch (e) {
    snackbarStore.setMsg(e.message)
  } finally {
    loading.value = false
  }
}

const getData = async() => {
  try {
    const resPt = await productService.getProductTypes()

    productTypes.value = resPt?.data

    const res = await productService.getOneProduct(props.uuid)
    const resData = res?.data

    data.value.name = resData?.name
    data.value.batch_number = resData?.batch_number
    data.value.product_type_id = resData?.product_type?.id
    data.value.pack_stok = resData?.pack_stok
    data.value.pack_price = resData?.pack_price
    data.value.items_per_pack = resData?.items_per_pack
    data.value.item_price = resData?.item_price
    data.value.total_item = resData?.total_item
    data.value.expired_date = resData?.expired_date
  } catch (e) {
    snackbarStore.setMsg(e.message)
  }
}

onMounted(getData)
</script>
