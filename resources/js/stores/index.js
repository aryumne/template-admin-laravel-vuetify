
import useAuthenticationStore from './authentication'
import useCkeditorStore from './ckeditor'

const authStore = useAuthenticationStore()
const ckeditorStore = useCkeditorStore()

export { authStore, ckeditorStore }
