<template>
  <TableCard title="List Product">
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
          <VBtn
            :to="{ name: 'blogs' }"
            type="submit"
          >
            Add Product
          </VBtn>
        </template>
        <template #tbody>
          <tr v-if="loading">
            <td colspan="4">
              <Loading />
            </td>
          </tr>
          <RouterLink
            v-for="user in rows"
            v-else
            :key="user.id"
            v-slot="{ navigate }"
            :to="{ name: 'blogs'}"
            custom
          >
            <tr
              role="link"
              @click="navigate"
              @keypress.enter="navigate"
            >
              <td class="checkbox-column">
                <VCheckbox
                  v-model="selectedRow.selected_ids"
                  type="checkbox"
                  class="dt-checkboxes form-check-input"
                  :value="user.id"
                  @click.stop
                />
              </td>
              <td>
                <div class="d-flex justify-content-start align-items-center user-name">
                  <div class="d-flex flex-column">
                    <a
                      href="#"
                      class="text-body text-truncate"
                      data-bs-toggle="tooltip"
                      data-bs-offset="0,4"
                      data-bs-placement="top"
                      data-bs-html="true"
                      title="<span>Show details</span>"
                    >
                      <span class="fw-semibold">{{ user.name ?? user.profile?.name }}</span>
                    </a>
                    <small class="text-muted">{{ user.email }}</small>
                  </div>
                </div>
              </td>
              <td>
                <RouterLink
                  :to="{ name: 'blogs'}"
                  class="btn btn-icon btn-transparent d-inline ms-2 text-info"
                >
                  <i
                    class="bx bx-show"
                    data-bs-toggle="tooltip"
                    data-bs-offset="0,4"
                    data-bs-placement="top"
                    data-bs-html="true"
                    title="Detail"
                  />
                </RouterLink>
                <RouterLink
                  :to="{ name: 'blogs'}"
                  class="btn btn-icon btn-transparent d-inline ms-2 text-warning"
                >
                  <i
                    class="bx bx-edit"
                    data-bs-toggle="tooltip"
                    data-bs-offset="0,4"
                    data-bs-placement="top"
                    data-bs-html="true"
                    title="Edit"
                  />
                </RouterLink>
              </td>
            </tr>
          </RouterLink>
        </template>
      </Datatable>
    </template>
  </TableCard>
  <ImportForm
    v-if="openModal"
    :is-show="openModal"
    @toggle-modal="toggleModal"
    @set-alert="snackbarStore.setMsg"
  />
</template>

<script setup>
import Datatable from '@/components/Datatable.vue'
import Loading from '@/components/Loading.vue'
import TableCard from '@/layouts/components/TableCard.vue'
import paths from '@services/paths.js'
import { snackbarStore } from '@stores'
import { ref, watch } from 'vue'
import ImportForm from './ImportForm.vue'

const heads = ref([
  {
    display_text: 'User',
    sortable: true,
    key: 'name',
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

const toggleModal = () => {
  openModal.value = !openModal.value
}

const childRef = ref()

watch(openModal, async (newVal, oldVal) => {
  if (newVal === false) {
    await childRef.value.getData()
  }
})

const rows = ref([
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
])

const setRows = data => {
  rows.value = [ {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  },
  {
    id: "123141sads",
    name: "aryum",
    email: "aryum@gmail.com",
  }]
}

const loading = ref(false)

const isLoading = data => {
  loading.value = data
}

const statusUser = expiredDate => {
  if (expiredDate === null) return true
  const expired = new Date(expiredDate)
  const currentDate = new Date()

  return currentDate < expired
}
</script>
