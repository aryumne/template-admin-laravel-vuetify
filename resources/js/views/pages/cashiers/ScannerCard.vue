<template>
  <VCard>
    <VCardItem>
      <VContainer>
        <VTextField
          type="text"
          density="compact"
          variant="outlined"
          placeholder="search product..."
          aria-controls="data"
        />
      </VContainer>
      <VDivider />
      <VTable>
        <thead>
          <tr>
            <th class="text-uppercase text-start">
              Obat
            </th>
            <th>
              satuan
            </th>
            <th>
              jumlah
            </th>
            <th>
              <div class="text-end">
                Total
              </div>
            </th>
          </tr>
        </thead>
  
        <tbody>
          <tr
            v-for="item in data"
            :key="item.barcode"
          >
            <td class="text-start">
              {{ item.name }}
            </td>
            <td class="text-center">
              <VSelect
                density="compact"
                :value="item.type"
                class="custom-width mx-auto"
                :items="['box', 'pcs']"
              />
            </td>
            <td class="text-center">
              <VTextField
                :value="item.quantity"
                density="compact"
                type="number"
                class="custom-width mx-auto"
                @update:model-value="updateQuantity(item, $event)"
              />
            </td>
            <td class="text-end">
              Rp. {{ numberWithCommas(item.price * item.quantity) }}
            </td>
          </tr>
          <tr>
            <td
              colspan="3"
              class="text-center font-weight-bold bg-grey-100"
            >
              TOTAL
            </td>
            <td class="text-end font-weight-bold">
              Rp. {{ numberWithCommas(totalOrders) }}
            </td>
          </tr>
        </tbody>
      </VTable>
      <VContainer class="d-flex justify-end">
        <VBtn color="success">
          Simpan
        </VBtn>
      </VContainer>
    </VCardItem>
  </VCard>
</template>

<script setup>
import { ref } from "vue"

const data = ref([
  {
    barcode: "12384123",
    name: "Komix herbal",
    type: "pcs",
    quantity: 3,
    price: 3000,
  },
  {
    barcode: "98384123",
    name: "Bodrex",
    type: "pcs",
    quantity: 2,
    price: 2500,
  },
  {
    barcode: "68384123",
    name: "Amoxcillin",
    type: "box",
    quantity: 1,
    price: 32000,
  },
  {
    barcode: "23384123",
    name: "asdasd",
    type: "pcs",
    quantity: 3,
    price: 15000,
  },
])

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}

function updateQuantity(item, value) {
  item.quantity = parseInt(value, 10)
}

const totalOrders = computed(() => {
  return data.value.reduce((sum, item) => sum + item.quantity * item.price, 0)
})
</script>

<style scoped>
.custom-width {
  padding: 0;
  max-inline-size: 100px;
}
</style>
