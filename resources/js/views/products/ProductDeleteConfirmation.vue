<template>
  <VCard>
    <VForm @submit.prevent="save">
      <VCardItem class="px-8 mb-4">
        <VCardTitle class="mt-4 text-center">
          <span class="text-h6">Konfirmasi</span>
        </VCardTitle>
        <VContainer>
          <div class="text-body-1">
            Apakah anda yakin menghapus data ini?
          </div>
        </VContainer>
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
              color="error"
              variant="tonal"
            >
              Hapus
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
import { ref } from 'vue'


const props = defineProps({
  dialog: Boolean,
  uuid: String,
})

const emitEvents = defineEmits(['closeDialog'])

const loading= ref(false)

const save = async() => {
  try {
    loading.value = true

    const res = await productService.deleteProduct(props.uuid)
    
    snackbarStore.setMsg(res?.message)
    emitEvents('closeDialog')
  } catch (e) {
    snackbarStore.setMsg(e.message)
  } finally {
    loading.value = false
  }
}
</script>
