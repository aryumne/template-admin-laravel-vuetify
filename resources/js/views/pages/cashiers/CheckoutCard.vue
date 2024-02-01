<template>
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
</template>
