<script setup>
import { authStore, snackbarStore } from '@stores'


const signOut = async () => {
  try {
    const res = await axios.post('/logout')

    if (res.status === 200) await authStore.signOut()
    snackbarStore.setMsg(res.data.message)
  } catch (error) {
    snackbarStore.setMsg(error.message)
  } 
}
</script>

<template>
  <VAvatar
    class="cursor-pointer"
    color="primary"
    variant="tonal"
  >
    <VIcon icon="mdi-account" />

    <!-- SECTION Menu -->
    <VMenu
      activator="parent"
      width="230"
      location="bottom end"
      offset="14px"
    >
      <VList>
        <!-- 👉 User Avatar & Name -->
        <VListItem>
          <template #prepend>
            <VListItemAction start>
              <VAvatar
                color="primary"
                variant="tonal"
              >
                <VIcon icon="mdi-account" />
              </VAvatar>
            </VListItemAction>
          </template>

          <VListItemTitle class="font-weight-semibold">
            {{ authStore.getUser.name }}
          </VListItemTitle>
        </VListItem>
        <VDivider class="my-2" />

        <!-- 👉 Logout -->
        <VListItem @click.prevent="signOut">
          <template #prepend>
            <VIcon
              class="me-2"
              icon="bx-log-out"
              size="22"
            />
          </template>

          <VListItemTitle>
            Logout
          </VListItemTitle>
        </VListItem>
      </VList>
    </VMenu>
    <!-- !SECTION -->
  </VAvatar>
</template>
