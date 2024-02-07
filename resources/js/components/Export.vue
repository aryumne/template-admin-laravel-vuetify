<template>
  <VBtn
    v-if="propsEx.isAll"
    type="button"
    color="secondary"
    variant="tonal"
    class="ms-2"
    :disabled="loading"
    :loading="loading"
    @click.prevent="downloadSelectedRow"
  >
    <VIcon
      size="small"
      class="me-2"
      color="green-darken-2"
      icon="mdi-export-variant"
    /> Export Selected
  </VBtn>
  <VDialog
    v-else
    v-model="dialog"
    persistent
    width="500"
  >
    <template #activator="{ props }">
      <VBtn
        type="button"
        color="secondary"
        variant="tonal"
        class="ms-2"

        v-bind="props"
      >
        <VIcon
          size="small"
          class="me-2"
          color="green-darken-2"
          icon="mdi-export-variant"
        /> Export
      </VBtn>
    </template>
    <VCard>
      <VCardTitle class="mt-4">
        <span class="text-h6">Export berdasarkan tanggal input data</span>
      </VCardTitle>
      <VForm @submit.prevent="download">
        <VCardItem class="px-8 mb-4 ">
          <VRow>
            <VCol>
              <div class="text-subtitle-2 text-medium-emphasis">
                Tanggal awal
              </div>
              <VTextField
                v-model="data.start_date"
                type="date"
                :rules="rules.dateRules"
                required
              />
            </VCol>
            <VCol cols="12">
              <div class="text-subtitle-2 text-medium-emphasis">
                Tanggal akhir
              </div>
              <VTextField
                v-model="data.end_date"
                type="date"
                :rules="rules.dateRules"
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
                Download
              </VBtn>
            </VCol>
          </VRow>
        </VCardItem>
      </VForm>
    </VCard>
  </VDialog>
</template>

<script setup>
import { downloadExcel } from "@/services/blobApi"
import { snackbarStore } from "@/stores"
import { ref } from "vue"

const propsEx = defineProps({
  paths: String,
  isAll: Boolean,
  selectedIds: Array,
  fileName: String,
})


// Download for select items
const loading = ref(false)

const downloadSelectedRow = async () => {
  try {
    loading.value = true

    const url = await downloadExcel(propsEx.paths, { is_by_selected: 1, selected_ids: propsEx.selectedIds })

    getAsFile(url)
  } catch (e) {
    snackbarStore.setMsg(e.message)
  } finally {
    loading.value = false
  }
}


// Download for all

const dialog = ref(false)

const data = ref({
  start_date: null,
  end_date: null,
})

const rules = ref({
  dateRules: [value => {
    if (value) return true

    return 'Tanggal kedaluarsa harus diisi!'
  }],
})

const resetForm = () => {
  data.value = {
    start_date: null,
    end_date: null,
  }
}

const download = async () => {
  try {
    loading.value = true

    const url = await downloadExcel(propsEx.paths, { is_by_selected: 0, ...data.value })

    getAsFile(url)
    resetForm()
    dialog.value = false
  } catch (e) {
    snackbarStore.setMsg(e.message)
  } finally {
    loading.value = false
  }
}

const getAsFile = url => {
  const link = document.createElement('a')
    
  link.href = url
  link.setAttribute('download', `${propsEx.fileName}.xlsx`)
  document.body.appendChild(link)
  link.click()

  // Release the URL object
  window.URL.revokeObjectURL(url)

}

onMounted(() => {
  resetForm()
})
</script>
