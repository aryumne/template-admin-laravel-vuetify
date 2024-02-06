<script setup>
import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue'
import Footer from '@/layouts/components/Footer.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import VerticalNavLayout from '@layouts/components/VerticalNavLayout.vue'
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
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

        <IconBtn
          class="ms-n1"
          @click="router.push({name:'cashier'})"
        >
          <VIcon icon="mdi-point-of-sale" />
        </IconBtn>

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
          icon: 'mdi-text-box',
          to: {name:'products'},
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Riwayat Transaksi',
          icon: 'mdi-clipboard-text-clock',
          to: {name:'transactions'},
        }"
      />
      <VerticalNavSectionTitle
        :item="{
          heading: 'Laporan',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Laporan Pembelian',
          icon: 'mdi-text-box-multiple',
          to: {name:'purchases'},
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Laporan Penjualan',
          icon: 'mdi-text-box-multiple',
          to: {name:'sales'},
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
