<template>
  <TableCard title="Daftar Obat">
    <template #content>
      <Datatable
        ref="childRef"
        :table-heads="heads"
        :path-api="paths.products"
        :selected-all="selectedRow.is_all"
        need-checkbox
        need-action-row
        @use-is-loadings="isLoading"
        @use-set-rows="setRows"
        @use-set-alert="snackbarStore.setMsg"
        @toggle-select-all="toggleSelectAll"
      >
        <template #table-button>
          <VBtn
            v-if="selectedRow.is_all"
            type="button"
            color="secondary"
            variant="tonal"
            class="ms-2"
            :disabled="downloadLoading"
            :loading="downloadLoading"
            @click.prevent="downloadSelectedRow"
          >
            <VIcon
              size="small"
              class="me-2"
              color="green-darken-2"
              icon="mdi-export-variant"
            /> Export Selected
          </VBtn>
          <ExportForm v-else />
          <RouterLink :to="{name:'productsAddStock'}">
            <VBtn
              type="button"
              color="primary"
              variant="tonal"
              class="mx-2"
            >
              Tambah Stock
            </VBtn>
          </RouterLink>
          <ProductForm />
        </template>
        <template #tbody>
          <tr v-if="loading">
            <td colspan="4">
              <Loading />
            </td>
          </tr>
          <tr
            v-for="item in rows"
            v-else
            :key="item.id"
            role="link"
          >
            <td class="checkbox-column">
              <VCheckbox
                v-model="selectedRow.selected_ids"
                type="checkbox"
                class="dt-checkboxes form-check-input"
                :value="item.id"
              />
            </td>
            <td>
              <div class="text-body-1">
                {{ item.name }}
              </div>
              <div class="text-caption">
                {{ item.barcode }}
              </div>
            </td>
            <td>
              {{ item.expired_date }}
            </td>
            <td>
              {{ item.product_type.name }}
            </td>
            <td>
              {{ item.batch_number }}
            </td>
            <td>
              {{ item.pack_stok }}
            </td>
            <td>
              {{ item.total_item }}
            </td>
            <td class="text-end">
              Rp. {{ currencyFormat(item.item_price) }}
            </td>
            <td class="text-center">
              <VBtn
                density="compact"
                variant="text"
                color="warning"
                icon="mdi-pencil-outline"
                @click="openEditForm(item.id)"
              />
              <VBtn
                density="compact"
                variant="text"
                color="error"
                icon="mdi-delete-outline"
                @click="openDeleteConfirmation(item.id)"
              />
            </td>
          </tr>
        </template>
      </Datatable>
    </template>
  </TableCard>
  <VDialog
    v-model="dialogEdit"
    persistent
    width="1024"
  >
    <ProductEditFrom
      v-if="dialogEdit"
      :uuid="uuid"
      @close-dialog="closeEditForm"
    />
  </VDialog>
  <VDialog
    v-model="dialogDelete"
    persistent
    width="auto"
  >
    <ProductDeleteConfirmation
      v-if="dialogDelete"
      :uuid="uuid"
      @close-dialog="closeDeleteConfirmation"
    />
  </VDialog>
</template>

<script setup>
import Datatable from '@/components/Datatable.vue'
import Loading from '@/components/Loading.vue'
import TableCard from '@/layouts/components/TableCard.vue'
import { productService } from '@/services'
import { currencyFormat } from '@/utils'
import paths from '@services/paths.js'
import { snackbarStore } from '@stores'
import { ref, watch } from 'vue'
import ProductDeleteConfirmation from './ProductDeleteConfirmation.vue'
import ProductEditFrom from './ProductEditFrom.vue'
import ProductForm from './ProductForm.vue'
import ExportForm from './ExportForm.vue'


// Datatable heads
const heads = ref([
  {
    display_text: 'Nama obat',
    sortable: true,
    key: 'name',
  },
  {
    display_text: 'Tgl Kedaluarsa',
    sortable: true,
    key: 'expired_date',
  },
  {
    display_text: 'Jenis Obat',
    sortable: true,
    key: 'product_type_id',
  },
  {
    display_text: 'Nomor Batch',
    sortable: true,
    key: 'batch_number',
  },
  {
    display_text: 'Stok Box',
    sortable: true,
    key: 'pack_stok',
  },
  {
    display_text: 'Total pcs',
    sortable: true,
    key: 'total_item',
  },
  {
    display_text: 'Harga per Pcs',
    sortable: true,
    key: 'item_price',
  },
])

// end Datatable heads

// Download selection
const selectedRow = ref({
  is_all: false,
  selected_ids: [],
})

const toggleSelectAll = () => {
  selectedRow.value.is_all = !selectedRow.value.is_all
  if (selectedRow.value.is_all) {
    selectedRow.value.selected_ids = rows.value.map(item => item.id)
  } else {
    selectedRow.value.selected_ids = []
  }
}

watch(
  selectedRow,
  (newVal, oldVal) => {
    if (newVal.selected_ids.length === 0) {
      selectedRow.value.is_all = false
    } else {
      selectedRow.value.is_all = true
    }
  },
  { deep: true },
)

const downloadLoading = ref(false)

const downloadSelectedRow = async () => {
  try {
    downloadLoading.value = true

    const url = await productService.downloadProduct({ is_by_selected: 1, selected_ids: selectedRow.value.selected_ids })
    const link = document.createElement('a')

    link.href = url
    link.setAttribute('download', 'data_obat.xlsx')
    document.body.appendChild(link)
    link.click()

    // Release the URL object
    window.URL.revokeObjectURL(url)
  } catch (e) {
    Swal.fire('Oops!', e.message, 'error')
  } finally {
    downloadLoading.value = false
  }
}

// end Download selection

const childRef = ref()
const dialogEdit = ref(false)
const uuid = ref(null)

const openEditForm = id => {
  uuid.value = id
  dialogEdit.value = true
}

const closeEditForm = () => {
  dialogEdit.value = false
  uuid.value = null
}

watch(dialogEdit, async (newVal, oldVal) => {
  if (newVal === false) {
    await childRef.value.getData()
  }
})

const dialogDelete = ref(false)

const openDeleteConfirmation = id => {
  uuid.value = id
  dialogDelete.value = true
}

const closeDeleteConfirmation = () => {
  uuid.value = null
  dialogDelete.value = false
}

watch(dialogDelete, async (newVal, oldVal) => {
  if (newVal === false) {
    await childRef.value.getData()
  }
})

const rows = ref([])

const setRows = data => {
  rows.value = data
}

const loading = ref(false)

const isLoading = data => {
  loading.value = data
}
</script>
