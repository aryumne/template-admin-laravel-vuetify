import { apiGet, apiPost } from "./httpApi"

const getBlogs = async (params = {}) => await apiGet('blogs', params)
const getBlogTypes = async (params = {}) => await apiGet('blog-types', params)
const store = async (body, params = {}) => await  apiPost('blogs', body, params)

export const blogService = { getBlogs, getBlogTypes, store }
