import { defineStore } from "pinia"
import { ref } from "vue"

export default defineStore('products', () => {
  const products = ref([])
  function add(barcode) {
    const newProduct = {
      barcode: barcode,
      rows: 1,
      cols: 4,
    }

    products.value.push(newProduct)
  }

  function updateRows(barcode, value, type ) {
    const product = products.value.find(product => product.barcode === barcode)
    if (type === 'rows') {
      product.rows = parseInt(value)
    } else {
      product.cols = parseInt(value)
    }

  }
    
  function removeProduct(barcode) {
    try {
      const indexOfOrder = products.value.findIndex(product => product.barcode === barcode)
      if(indexOfOrder !== -1) products.value.splice(indexOfOrder, 1)
    } catch (error) {
      console.error(error)
      throw error
    }
  }

  function resetProduct() {
    products.value = []
  }
  
  return { products, add, updateRows, removeProduct, resetProduct }
})
