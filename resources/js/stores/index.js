
import useAuthenticationStore from './authentication'
import useBarcodeStore from './barcode'
import useCkeditorStore from './ckeditor'
import useOrderStore from './order'
import useSnackbarStore from './snackbar'

const authStore = useAuthenticationStore()
const ckeditorStore = useCkeditorStore()
const snackbarStore = useSnackbarStore()
const orderStore = useOrderStore()
const barcodeStore = useBarcodeStore()

export { authStore, barcodeStore, ckeditorStore, orderStore, snackbarStore }

