import { apiGet } from "./httpApi"
import paths from "./paths"

const getAnalytics = async (params = {}) => await apiGet(paths.analytics, params)

export const dashboardService = {
  getAnalytics,
}
