<template>
  <TableCard title="Daftar Pembelian Stock">
    <template #content>
      <Datatable
        ref="childRef"
        :table-heads="heads"
        :path-api="paths.purchases"
        :selected-all="selectedRow.is_all"
        need-checkbox
        @use-is-loadings="isLoading"
        @use-set-rows="setRows"
        @use-set-alert="snackbarStore.setMsg"
        @toggle-select-all="toggleSelectAll"
      >
        <template #table-button>
          <Export
            :is-all="selectedRow.is_all"
            :paths="paths.purchasesDownload"
            :selected-ids="selectedRow.selected_ids"
            file-name="laporan-pembelian-stok"
          />
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
              {{ item.product.name }}
            </td>
            <td>
              {{ item.quantity }}
            </td>
            <td>
              Rp. {{ currencyFormat(item.price ) }}
            </td>
            <td>
              Rp. {{ currencyFormat(item.total_price) }}
            </td>
          </tr>
        </template>
      </Datatable>
    </template>
  </TableCard>
</template>

<script setup>
import Datatable from '@/components/Datatable.vue'
import Export from '@/components/Export.vue'
import Loading from '@/components/Loading.vue'
import TableCard from '@/layouts/components/TableCard.vue'
import { currencyFormat } from '@/utils'
import paths from '@services/paths.js'
import { snackbarStore } from '@stores'
import { ref, watch } from 'vue'

const heads = ref([
  {
    display_text: 'Nama obat',
    sortable: true,
    key: 'name',
  },
  {
    display_text: 'Jumlah',
    sortable: true,
    key: 'quantity',
  },
  {
    display_text: 'Harga Satuan',
    sortable: true,
    key: 'price',
  },
  {
    display_text: 'Total Harga',
    sortable: true,
    key: 'total_price',
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

const rows = ref([])

const setRows = data => {
  rows.value = data
}

const loading = ref(false)

const isLoading = data => {
  loading.value = data
}
</script>
