<script setup>

const isCardDetailsVisible = ref(false)
</script>

<template>
  <VRow>
    <!-- 👉 Influencing The Influencer -->
    <VCol
      cols="12"
      lg="8"
      md="7"
    >
      <VCard>
        <VCardItem>
          <VTextField
            type="text"
            density="compact"
            variant="outlined"
            placeholder="search product..."
            aria-controls="data"
            @input="searchTyping"
          />
        </VCardItem>
      </VCard>
    </VCol>

    <!-- 👉 The Best Answers -->
    <VCol
      cols="12"
      lg="4"
      md="5"
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
