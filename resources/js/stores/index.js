
import useAuthenticationStore from './authentication'
import useCkeditorStore from './ckeditor'
import useSnackbarStore from './snackbar'

const authStore = useAuthenticationStore()
const ckeditorStore = useCkeditorStore()
const snackbarStore = useSnackbarStore()

export { authStore, ckeditorStore, snackbarStore }
