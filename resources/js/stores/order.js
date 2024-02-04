import { OrderTypeEnum } from "@/configs"
import { productService } from "@/services"
import { defineStore } from "pinia"
import { ref } from "vue"

export default defineStore('order', () => {
  const orders = ref([])
    
  async function add(barcode) {
    try {
      let existingOrder = orders.value.find(order => order.barcode === barcode)
      if (existingOrder) {
        existingOrder.quantity++
        
        existingOrder.total_price = existingOrder.quantity * existingOrder.price
      } else {
        const res = await productService.searchByBarcode(barcode)

        const newOrder = {
          name: res?.data.name,
          barcode: res?.data.barcode,
          type: OrderTypeEnum.PCS,
          quantity: 1,
          price: res?.data.item_price,
          total_price: res?.data.item_price,
        }

        orders.value.push(newOrder)          
      }
    } catch (error) {
      console.error(error)
      throw error
    }
  }

  async function changeType(barcode, type) {
    try {
      const res = await productService.searchByBarcode(barcode)
      let order = orders.value.find(order => order.barcode === barcode)
      order.type = type
      if (order.type === OrderTypeEnum.PACK) {
        order.price = res.data.pack_price
        order.total_price = res.data.pack_price + order.quantity
      } else {
        order.price = res.data.item_price
        order.total_price = res.data.item_price + order.quantity
      }
    } catch (error) {
      console.error(error)
      throw error
    }
  }

  function changeQuantity(barcode, newQuantity) {
    try {
      let order = orders.value.find(order => order.barcode === barcode)
      console.log(newQuantity)
      if (newQuantity <= 0) {
        order.quantity = 1
        order.total_price = 1 * order.price   
      } else {
        order.quantity = newQuantity
        order.total_price = newQuantity * order.price   
      }
    } catch (error) {
      console.error(error)
      throw error
    }
  }
  
  function removeOrder(barcode) {
    try {
      const indexOfOrder = orders.value.findIndex(order => order.barcode === barcode)
      if(indexOfOrder !== -1) orders.value.splice(indexOfOrder, 1)
    } catch (error) {
      console.error(error)
      throw error
    }
  }

  function resetOrder() {
    orders.value = []
  }
  
  return { orders, add, changeType, changeQuantity, removeOrder, resetOrder }
})
