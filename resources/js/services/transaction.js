import { downloadPdf } from "./blobApi"
import { apiGetOne, apiPatch, apiPost } from "./httpApi"
import paths from "./paths"

const storeOrder = async (body, params = {}) => await  apiPost(paths.transactions, body, params)
const getOneTransaction = async (id, params = {}) => await apiGetOne(paths.transactions, id, params)
const updateTransaction = async (id, body, params = {}) => await apiPatch(paths.transactions, id, body, params)
const downloadInvoice = async (id, params = {}) => await downloadPdf(paths.transactionsInvoice, id, params)

export const transactionService = { getOneTransaction, storeOrder, updateTransaction, downloadInvoice }
