<template>
  <VRow>
    <VCol
      cols="12"
      md="6"
      lg="6"
      order-md="first"
      order="last"
    >
      <VTextField
        v-model="configParams.search"
        type="text"
        density="compact"
        variant="outlined"
        placeholder="search..."
        aria-controls="data"
        @input="searchTyping"
      />
    </VCol>
    <VCol
      cols="12"
      md="6"
      lg="6"
      order-md="last"
      order="first"
    >
      <VContainer class="d-flex py-0 justify-md-end justify-sm-start px-0">
        <div class="my-auto text-body-1 datatables-text-info">
          Show
        </div>
        <VSelect
          v-model="configParams.entries"
          density="compact"
          class="entries-select mx-2"
          :items="[5,10,25,50,100]"
          @update:model-value="changeEntries"
        />
        <div class="my-auto text-body-1 datatables-text-info">
          entries
        </div>
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
            <div class="d-flex justify-content-between">
              {{ head.display_text }}
              <div
                v-if="head.sortable"
                class="d-flex flex-column"
              >
                <i
                  class="bx bxs-chevron-up icon-sort datatables-text-info"
                  :class="{
                    'sort-active':
                      configParams.sort.field == head.key && configParams.sort.direction == 'asc'
                  }"
                  @click.prevent="sortName(head.key, 'asc')"
                />
                <i
                  class="bx bxs-chevron-down icon-sort datatables-text-info"
                  :class="{
                    'sort-active':
                      configParams.sort.field == head.key && configParams.sort.direction == 'desc'
                  }"
                  @click.prevent="sortName(head.key, 'desc')"
                />
              </div>
            </div>
          </th>
          <th>Action</th>
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
      <div class="datatables-text-info">
        Showing {{ data.from }} to {{ data.to }} of {{ data.total }} entries
      </div>
    </VCol>
    <VCol
      cols="12"
      md="7"
    >
      <div class="d-flex justify-md-end justify-sm-center">
        <div class="text-center">
          <VPagination
            v-model="configParams.page"
            :length="totalPage"
          />
        </div>
      </div>
    </VCol>
  </VRow>
</template>

<script setup>
import { CONSTANTS } from '@configs/index.js'
import { apiGet } from '@services/httpApi.js'
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
  pathApi: String,
  tableHeads: Array,
  selectedAll: Boolean,
  needCheckbox: Boolean,
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
    totalPage.value = res.last_page
  } catch (e) {
    data.value = []
    emitEvents('useSetAlert', { isShow: true, type: 'warning', msg: e.message })
  } finally {
    emitEvents('useSetRows', data.value.data)
    emitEvents('useIsLoading', false)
  }
}

defineExpose({ getData })

const changeEntries = () => {
  configParams.value.page = 1
  getData()
}

const searchTyping = () => {
  configParams.value.page = 1
  getData()
}

const nextPage = () => {
  configParams.value.page++
  getData()
}

const toPage = page => {
  configParams.value.page = page
  getData()
}

const prevPage = () => {
  configParams.value.page--
  getData()
}

onMounted(getData)
</script>

<style scoped>
.entries-select {
  max-width: 70px !important;
}
.datatables-text-info {
  color: #a1acb8;
}
.page-link {
  padding: 0.625rem 0.5125rem;
  min-width: calc(2rem + 0px);
  font-size: 0.75rem;
  line-height: 1;
}
.icon-sort {
  margin: 0;
  padding: 0;
  font-size: 1rem !important;
  line-height: 0.7 !important;
}
.sort-active {
  color: #696cff !important;
}
</style>