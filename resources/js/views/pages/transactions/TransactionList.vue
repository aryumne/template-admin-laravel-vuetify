<template>
  <TableCard title="Daftar Transaksi">
    <template #content>
      <Datatable
        ref="childRef"
        :table-heads="heads"
        :path-api="paths.transactions"
        :selected-all="selectedRow.is_all"
        need-checkbox
        @use-is-loadings="isLoading"
        @use-set-rows="setRows"
        @use-set-alert="snackbarStore.setMsg"
        @toggle-select-all="toggleSelectAll"
      >
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
              {{ item.transaction_number }}
            </td>
            <td>
              {{ item.prescription_number == null ? '-': item.prescription_number }}
            </td>
            <td>
              Rp. {{ currencyFormat(item.amount) }}
            </td>
            <td>
              Rp. {{ currencyFormat(item.cash_amount) }}
            </td>
            <td>
              Rp. {{ currencyFormat(item.return_amount ) }}
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

const heads = ref([
  {
    display_text: 'Kode transaksi',
    sortable: true,
    key: 'transaction_number',
  },
  {
    display_text: 'Nomor Resep',
    sortable: true,
    key: 'prescription_number',
  },
  {
    display_text: 'Total',
    sortable: true,
    key: 'amount',
  },
  {
    display_text: 'Tunai',
    sortable: true,
    key: 'cash_amount',
  },
  {
    display_text: 'Kembalian',
    sortable: true,
    key: 'return_amount',
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
