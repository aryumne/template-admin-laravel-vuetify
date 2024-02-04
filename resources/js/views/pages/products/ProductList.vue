<template>
  <TableCard title="Daftar Obat">
    <template #content>
      <Datatable
        ref="childRef"
        :table-heads="heads"
        :path-api="paths.products"
        :selected-all="selectedRow.is_all"
        need-checkbox
        @use-is-loadings="isLoading"
        @use-set-rows="setRows"
        @use-set-alert="snackbarStore.setMsg"
        @toggle-select-all="toggleSelectAll"
      >
        <template #table-button>
          <VBtn
            type="button"
            color="secondary"
            variant="tonal"
            class="mx-2"
          >
            <VIcon
              size="small"
              class="me-2"
              color="green-darken-2"
              icon="mdi-export-variant"
            /> Export
          </VBtn>
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
              {{ item.barcode }}
            </td>
            <td>
              {{ item.name }}
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
              <RouterLink :to="{ name: 'blogs'}">
                <VBtn
                  density="compact"
                  variant="text"
                  color="info"
                  icon="mdi-eye"
                />
              </RouterLink>
              <RouterLink :to="{ name: 'blogs'}">
                <VBtn
                  density="compact"
                  variant="text"
                  color="warning"
                  icon="mdi-pencil-outline"
                />
              </RouterLink>
            </td>
          </tr>
        </template>
      </Datatable>
    </template>
  </TableCard>
</template>

<script setup>
import Datatable from '@/components/Datatable.vue'
import Loading from '@/components/Loading.vue'
import TableCard from '@/layouts/components/TableCard.vue'
import { currencyFormat } from '@/utils'
import paths from '@services/paths.js'
import { snackbarStore } from '@stores'
import { ref, watch } from 'vue'
import ProductForm from './ProductForm.vue'

const heads = ref([
  {
    display_text: 'Barcode',
    sortable: false,
    key: 'barcode',
  },
  {
    display_text: 'Nama obat',
    sortable: true,
    key: 'name',
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

const openModal = ref(false)
const childRef = ref()

watch(openModal, async (newVal, oldVal) => {
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
