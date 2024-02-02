import { apiGet, apiPost } from "./httpApi"

const getUser = async (params = {}) => await apiGet('auth/me', params)

const signIn = async (body, params = {}) => await  apiPost('login', body, params)
const signOut = async (body = {}, params = {}) => await  apiPost('logout', body, params)

export const authService = { getUser, signIn, signOut }
