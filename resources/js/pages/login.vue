<script setup>
import { snackbarStore } from '@/stores'
import logo from '@images/logo.svg?raw'
import { authStore } from '@stores'

const loading = ref(false)

const data = ref({
  email: '',
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
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      class="auth-card pa-4 pt-7"
      max-width="448"
    >
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="d-flex">
            <div
              class="d-flex text-primary"
              v-html="logo"
            />
          </div>
        </template>

        <VCardTitle class="text-2xl font-weight-bold">
          sneat
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          Welcome to sneat! 👋🏻
        </h5>
        <p class="mb-0">
          Please sign-in to your account and start the adventure
        </p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="signin">
          <VRow>
            <VCol cols="12">
              <VTextField
                v-model="data.email"
                autofocus
                placeholder="johndoe@email.com"
                label="Email"
                type="email"
              />
            </VCol>

            <VCol cols="12">
              <div class="d-flex align-center justify-end flex-wrap">
                <RouterLink
                  class="text-primary ms-2 mb-1"
                  to="javascript:void(0)"
                >
                  Forgot Password?
                </RouterLink>
              </div>
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
