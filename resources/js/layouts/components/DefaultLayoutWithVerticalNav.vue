<script setup>
import Footer from '@/layouts/components/Footer.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import VerticalNavLayout from '@layouts/components/VerticalNavLayout.vue'
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue'

// import upgradeBannerDark from '@images/pro/upgrade-banner-dark.png'
// import upgradeBannerLight from '@images/pro/upgrade-banner-light.png'
// const vuetifyTheme = useTheme()
// const upgradeBanner = computed(() => {
//   return vuetifyTheme.global.name.value === 'light' ? upgradeBannerLight : upgradeBannerDark
// })

const openLogViewer = () => window.location.href= '/log-viewer'
</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <IconBtn
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon icon="bx-menu" />
        </IconBtn>

        <NavbarThemeSwitcher />

        <VSpacer />

        

        <UserProfile />
      </div>
    </template>


    <template #vertical-nav-content>
      <VerticalNavLink
        :item="{
          title: 'Dashboard',
          icon: 'bx-home',
          to: {name:'dashboard'},
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Data Obat',
          icon: 'mdi-account-cog-outline',
          to: {name:'products'},
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Data Penjualan',
          icon: 'mdi-text-box-multiple',
          to: {name:'sales'},
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Data Transaksi',
          icon: 'mdi-text-box-multiple',
          to: {name:'transactions'},
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Kasir',
          icon: 'mdi-point-of-sale',
          to: {name: 'cashier', replace: true},
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Log Viewer',
          icon: 'mdi-file-document-alert',
          to: '/log-viewer',
        }"
        @click="openLogViewer"
      />
    </template>
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
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
