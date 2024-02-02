export * from './hashing'

export function currencyFormat(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
