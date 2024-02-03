
import useAuthenticationStore from './authentication'
import useCkeditorStore from './ckeditor'
import useOrderStore from './order'
import useSnackbarStore from './snackbar'

const authStore = useAuthenticationStore()
const ckeditorStore = useCkeditorStore()
const snackbarStore = useSnackbarStore()
const orderStore = useOrderStore()

export { authStore, ckeditorStore, orderStore, snackbarStore }

