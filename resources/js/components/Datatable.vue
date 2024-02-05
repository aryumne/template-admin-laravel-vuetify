<template>
  <VRow>
    <VCol
      cols="12"
      md="5"
      lg="4"
      order-md="first"
      order="last"
    >
      <VTextField
        v-model="configParams.search"
        type="text"
        density="compact"
        variant="outlined"
        placeholder="Cari di sini.."
        aria-controls="data"
        @input="searchTyping"
      />
    </VCol>
    <VCol
      cols="12"
      md="7"
      lg="8"
      order-md="last"
      order="first"
    >
      <VContainer class="d-flex py-0 align-center justify-md-end justify-sm-start px-0">
        <VSelect
          v-model="configParams.entries"
          density="compact"
          class="entries-select ms-2 p-0"
          :items="[5,10,25,50,100]"
          @update:model-value="changeEntries"
        />
        <slot name="table-button" />
      </VContainer>
    </VCol>
  </VRow>
  <VDivider class="mt-4" />
  <VResponsive class="mb-2">
    <VTable fixed-header>
      <thead>
        <tr>
          <th
            v-if="props.needCheckbox"
            class="checkbox-column"
          >
            <VCheckbox
              type="checkbox"
              class="dt-checkboxes form-check-input"
              :checked="props.selectedAll"
              @change="emitEvents('toggleSelectAll')"
            />
          </th>
          <th
            v-for="head in props.tableHeads"
            :key="head.key"
          >
            <div class="d-flex align-center">
              <div class="text-body-2 text-uppercase font-weight-bold">
                {{ head.display_text }}
              </div>
              <VSpacer />
              <div
                v-if="head.sortable"
                class="d-flex flex-column"
              >
                <VIcon
                  icon="mdi-chevron-up"
                  class="icon-sort datatables-text-info icon-sort-up"
                  :class="{
                    'sort-active':
                      configParams.sort.field == head.key && configParams.sort.direction == 'asc'
                  }"
                  @click.prevent="sortName(head.key, 'asc')"
                />
                <VIcon
                  icon="mdi-chevron-down "
                  class="icon-sort datatables-text-info icon-sort-down"
                  :class="{
                    'sort-active':
                      configParams.sort.field == head.key && configParams.sort.direction == 'desc'
                  }"
                  @click.prevent="sortName(head.key, 'desc')"
                />
              </div>
            </div>
          </th>
          <th v-if="props.needActionRow">
            Aksi
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="issetData">
          <td
            :colspan="props.tableHeads.length + 2"
            class="text-center"
          >
            {{ CONSTANTS.NO_DATA }}
          </td>
        </tr>
        <slot
          v-else
          name="tbody"
        />
      </tbody>
    </VTable>
  </VResponsive>
  <VRow>
    <VCol
      cols="12"
      md="5"
    >
      <div
        v-if="data.total > 0"
        class="datatables-text-info"
      >
        Menampilkan {{ data.from }} sampai {{ data.to }} dari {{ data.total }}
      </div>
      <div
        v-else
        class="datatables-text-info"
      >
        Belum ada data
      </div>
    </VCol>
    <VCol
      cols="12"
      md="7"
    >
      <div class="d-flex justify-md-end justify-sm-center">
        <div class="text-center">
          <VPagination
            v-if="totalPage > 0"
            v-model="configParams.page"
            :length="totalPage"
            :total-visible="6"
            @next="nextPage"
            @prev="prevPage"
          />
        </div>
      </div>
    </VCol>
  </VRow>
</template>

<script setup>
import { CONSTANTS } from '@configs/index.js'
import { apiGet } from '@services/httpApi.js'
import { computed, onMounted, ref, watch } from 'vue'

const props = defineProps({
  pathApi: String,
  tableHeads: Array,
  selectedAll: Boolean,
  needCheckbox: Boolean,
  needActionRow: Boolean,
})

const emitEvents = defineEmits(['useSetRows', 'useSetAlert', 'useIsLoading', 'toggleSelectAll'])
const data = ref([])
const totalPage = ref(0)

const configParams = ref({
  search: '',
  page: 1,
  entries: CONSTANTS.PAGINATE_DEFAULT,
  sort: {
    field: null,
    direction: null,
  },
})


const issetData = computed(() => {
  return data.value.data?.length === 0 ? true : false
})

const sortName = (field, direction) => {
  configParams.value.sort.field = field
  configParams.value.sort.direction = direction
  getData()
}

const getData = async () => {
  try {
    emitEvents('useIsLoading', true)

    const res = await apiGet(props.pathApi, {
      params: { ...configParams.value },
    })

    data.value = res
    totalPage.value = res?.last_page
  } catch (e) {
    data.value = []
    emitEvents('useSetAlert', e.message)
  } finally {
    emitEvents('useSetRows', data.value.data)
    emitEvents('useIsLoading', false)
  }
}

defineExpose({ getData })

watch(configParams, (newVal, oldVal) => {
  toPage(newVal.page)
}, { deep: true })

const changeEntries = () => {
  configParams.value.page = 1
  getData()
}

const searchTyping = () => {
  configParams.value.page = 1
  getData()
}

const nextPage = () => {
  getData()
}

const toPage = page => {
  configParams.value.page = page
  getData()
}

const prevPage = () => {
  getData()
}

onMounted(getData)
</script>

<style scoped>
.entries-select {
  max-inline-size: 70px !important;
}

.datatables-text-info {
  color: #a1acb8;
}

.page-link {
  font-size: 0.75rem;
  line-height: 1;
  min-inline-size: calc(2rem + 0px);
  padding-block: 0.625rem;
  padding-inline: 0.5125rem;
}

.icon-sort {
  padding: 0;
  font-size: 1rem !important;
  line-height: 0.5rem !important;
}

.icon-sort-up {
  margin-block-end: -0.1rem;
}

.icon-sort-down {
  margin-block-start: -0.1rem;
}

.sort-active {
  color: #696cff !important;
}
</style>
