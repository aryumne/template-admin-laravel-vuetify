import { apiDelete, apiGet, apiGetOne, apiPost } from "./httpApi"

const blogPath = 'blogs'
const blogTypePath = 'blog-types'
const getBlogTypes = async (params = {}) => await apiGet(blogTypePath, params)
const getBlogs = async (params = {}) => await apiGet(blogPath, params)
const getOneBlog = async (id, params = {}) => await apiGetOne(blogPath, id, params)
const storeBlog = async (body, params = {}) => await  apiPost(blogPath, body, params)
const deleteBlog = async (id, params ={}) => await apiDelete(blogPath, id, params)

export const blogService = { getBlogs, getOneBlog, getBlogTypes, storeBlog, deleteBlog }
