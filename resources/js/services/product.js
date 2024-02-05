import { apiDelete, apiGet, apiGetOne, apiPatch, apiPost } from "./httpApi"
import paths from "./paths"

const getProductTypes = async (params = {}) => await apiGet(paths.productTypes, params)
const getProducts = async (params = {}) => await apiGet(paths.products, params)
const getOneProduct = async (id, params = {}) => await apiGetOne(paths.products, id, params)
const storeProduct = async (body, params = {}) => await  apiPost(paths.products, body, params)
const updateProduct = async (id, body, params = {}) => await apiPatch(paths.products, id, body, params)
const deleteProduct = async (id, params = {}) => await apiDelete(paths.products, id, params)
const searchForOrder = async (params = {}) => await apiGet(paths.searchProducts, params)
const searchByBarcode = async (barcode, params = {}) => await apiGetOne(paths.searchProductByBarcode, barcode, params)
const storePurchases =  async (body, params = {}) => await apiPost(paths.purchases, body, params)

export const productService = { getProducts, getOneProduct, getProductTypes, storeProduct, updateProduct, deleteProduct, searchForOrder, searchByBarcode, storePurchases }
