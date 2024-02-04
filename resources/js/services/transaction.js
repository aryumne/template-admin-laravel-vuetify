import { apiGetOne, apiPost } from "./httpApi"
import paths from "./paths"

const storeOrder = async (body, params = {}) => await  apiPost(paths.transactions, body, params)
const getOneOrder = async (id, params = {}) => await apiGetOne(paths.transactions, id, params)

export const transactionService = { getOneOrder, storeOrder }
