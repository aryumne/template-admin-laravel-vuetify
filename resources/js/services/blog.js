import { apiDelete, apiGet, apiGetOne, apiPatch, apiPost } from "./httpApi"

const blogPath = 'blogs'
const blogGroupedPath = 'blogs-group'
const blogTypePath = 'blog-types'
const getBlogTypes = async (params = {}) => await apiGet(blogTypePath, params)
const getBlogs = async (params = {}) => await apiGet(blogPath, params)
const getBlogsWithGrouping = async (params = {}) => await apiGet(blogGroupedPath, params)
const getOneBlog = async (id, params = {}) => await apiGetOne(blogPath, id, params)
const storeBlog = async (body, params = {}) => await  apiPost(blogPath, body, params)
const updateBlog = async (id, body, params = {}) => await apiPatch(blogPath, id, body, params)
const deleteBlog = async (id, params ={}) => await apiDelete(blogPath, id, params)

export const blogService = { getBlogs, getBlogsWithGrouping, getOneBlog, getBlogTypes, storeBlog, updateBlog, deleteBlog }
