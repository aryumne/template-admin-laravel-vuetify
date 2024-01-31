<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import avatar2 from '@images/avatars/avatar-2.png'
import avatar3 from '@images/avatars/avatar-3.png'
import avatar4 from '@images/avatars/avatar-4.png'
import eCommerce2 from '@images/eCommerce/2.png'
import pages1 from '@images/pages/1.png'
import pages2 from '@images/pages/2.png'
import pages3 from '@images/pages/3.png'
import pages5 from '@images/pages/5.jpg'
import pages6 from '@images/pages/6.jpg'

const isCardDetailsVisible = ref(false)
</script>

<template>
  <VRow>
    <!-- 👉 Influencing The Influencer -->
    <VCol
      cols="12"
      lg="4"
      md="6"
    >
      <VCard title="Influencing The Influencer">
        <VCardText>
          Computers have become ubiquitous in almost every facet of our lives. At work, desk jockeys spend hours in front of their desktops, while delivery people scan bar codes with handhelds and workers in the field stay in touch.
        </VCardText>

        <VCardText>
          If you're in the market for new desktops, notebooks, or PDAs, there are a myriad of choices. Here's a rundown of some of the best systems available.
        </VCardText>

        <VCardActions>
          <VBtn>Read More</VBtn>
        </VCardActions>
      </VCard>
    </VCol>

    <!-- 👉 The Best Answers -->
    <VCol
      cols="12"
      lg="4"
      md="6"
    >
      <VCard title="The Best Answers">
        <VCardText class="d-flex align-center flex-wrap">
          <VRating
            :model-value="5"
            readonly
            density="compact"
            class="me-3"
          />
          <span class="text-subtitle-2">5 Star | 98 reviews</span>
        </VCardText>

        <VCardText>
          <p>
            If you are looking for a new way to promote your business that won't cost you more money, maybe printing is one of the options you won't resist.
          </p>

          <p class="mb-0">
            become fast, easy and simple. If you want your promotional material to be an eye-catching
          </p>
        </VCardText>

        <VCardActions>
          <VBtn>Location</VBtn>
          <VBtn>Reviews</VBtn>
        </VCardActions>
      </VCard>
    </VCol>

    <!-- 👉 Support -->
    <VCol
      cols="12"
      md="6"
      lg="4"
    >
      <VCard class="text-center">
        <VCardText class="d-flex flex-column justify-center align-center">
          <VAvatar
            color="primary"
            variant="tonal"
            size="50"
            class="mb-4"
          >
            <VIcon
              size="2rem"
              icon="bx-help-circle"
            />
          </VAvatar>

          <h6 class="text-h6">
            Support
          </h6>
        </VCardText>

        <VCardText>
          <p>
            According to us blisters are a very common thing and we come across them very often in our daily lives. It is a very common occurrence like cold or fever depending upon your lifestyle.
          </p>
        </VCardText>
        <VCardText>
          <VBtn variant="elevated">
            Contact Now
          </VBtn>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.avatar-center {
  position: absolute;
  border: 3px solid rgb(var(--v-theme-surface));
  inset-block-start: -2rem;
  inset-inline-start: 1rem;
}

// membership pricing
.member-pricing-bg {
  position: relative;
  background-color: rgba(var(--v-theme-on-surface), var(--v-hover-opacity));
}

.membership-pricing {
  sup {
    inset-block-start: 9px;
  }
}
</style>
