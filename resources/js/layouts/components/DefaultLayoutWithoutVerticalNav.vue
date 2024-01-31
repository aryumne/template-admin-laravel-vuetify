<script setup>
import Footer from '@/layouts/components/Footer.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import NavbarOnly from '@layouts/components/NavbarOnly.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
</script>

<template>
  <NavbarOnly>
    <!-- 👉 navbar -->
    <template #navbar>
      <div class="d-flex h-100 align-center">
        <IconBtn
          class="ms-n3"
          @click="router.push({name:'dashboard'})"
        >
          <VIcon icon="bx-home" />
        </IconBtn>

        <NavbarThemeSwitcher />

        <VSpacer />

        

        <UserProfile />
      </div>
    </template>
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </NavbarOnly>
</template>

<style lang="scss" scoped>
.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}
</style>
