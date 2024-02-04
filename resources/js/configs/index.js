export const baseUrl = import.meta.env.BASE_URL
export const apiVersion = `${import.meta.env.BASE_URL}api/${import.meta.env.VITE_API_VERSION}`
export const urlStorage = `${import.meta.env.BASE_URL}storage`
export const SECRETS = {
  SALT_CODE: 'mySalt',
  TOKEN_MAP_BOX:
    'pk.eyJ1IjoiYXJ5dW1uZSIsImEiOiJjbG01NGtweWsyZWFjM2VzNjh5OXRrZHRqIn0.mbeiWS8SaLOSKgC6Qg2y0A',
}

export const CONSTANTS = {
  PAGINATE_DEFAULT: import.meta.env.VITE_PAGINATE_VALUE || 5,
}


export const OrderTypeEnum = {
  PACK: 'pack',
  PCS: 'pcs',
}
