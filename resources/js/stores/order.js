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

        console.log(res.data)

        const newOrder = {
          id: res.data.id,
          name: res.data.name,
          barcode: res.data.barcode,
          type: "pcs",
          quantity: 1,
          price: res.data.item_price,
          total_price: res.data.item_price,
        }

        orders.value.push(newOrder)
        console.log(orders.value)
          
      }
    } catch (error) {
      console.log(error)
      throw e
    }
  }

  async function changeType(id, type) {
    try {
      const res = await productService.getOneProduct(id)
      let order = orders.value.find(order => order.id === id)
      order.type = type
      if (order.type === "pack") {
        order.price = res.data.pack_price
        order.total_price = res.data.pack_price + order.quantity
      }
      else {
        order.price = res.data.item_price
        order.total_price = res.data.item_price + order.quantity
      }
    } catch (error) {
      console.log(error)
      throw e 
    }
  }

  function changeQuantity(id, newQuantity) {
    try {
      let order = orders.value.find(order => order.id === id)
      order.quantity = newQuantity
      order.total_price = newQuantity * order.price   
    } catch (error) {
      console.log(error)
      throw e 
    }
  }
  
  return { orders, add, changeType, changeQuantity }
})
