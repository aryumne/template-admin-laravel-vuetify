<script setup>
import { snackbarStore } from '@/stores'
import logo from '@images/logo.png'
import { authStore } from '@stores'

const loading = ref(false)

const data = ref({
  password: '',
  remember: false,
})

const isPasswordVisible = ref(false)

const signin = async () => {
  try {
    loading.value = true
    await axios.get('/sanctum/csrf-cookie')

    const res = await axios.post('/login', data.value)
    if (res.headers['content-type'].includes('text/html')) throw new Error('Terjadi kesalahan saat login, silahkan coba lagi!')
    if (res.status === 200) await authStore.signin()
    snackbarStore.setMsg(res.data.message)
  } catch (error) {
    let errorMsg = error.message
    if(error?.response) errorMsg = error.response?.data.message
    snackbarStore.setMsg(errorMsg)
    authStore.signOut()
    loading.value = false
  } 
}
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      class="auth-card pa-4 pt-7"
      max-width="600"
    >
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="d-flex">
            <VAvatar
              rounded
              variant="tonal"
              color="primary"
            >
              <img
                :src="logo"
                class="img-fluid"
                width="50"
              >
            </VAvatar>
          </div>
        </template>

        <VCardTitle class="text-2xl font-weight-bold">
          SIRARM
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2 text-center">
        <h5 class="text-h5 mb-1 font-weight-bold">
          APOTIK REZKY MEDIKA
        </h5>
        <p class="mb-0 text-body-2">
          Jl. Gunung Salju, No. 14
        </p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="signin">
          <VRow>
            <VCol cols="12">
              <VTextField
                v-model="data.password"
                label="Password"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />

              <div class="text-center mt-4">
                <VBtn
                  block
                  type="submit"
                  :loading="loading"
                  @click="loading = !loading"
                >
                  Login

                  <template #loader>
                    <VProgressLinear indeterminate />
                  </template>
                </VBtn>
              </div>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
