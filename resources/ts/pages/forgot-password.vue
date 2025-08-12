<script setup lang="ts">
import { useRouter } from 'vue-router'
import { layoutConfig } from '@layouts'

const router = useRouter()

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})
</script>

<template>
  <div class="forgot-password-page d-flex align-center justify-center">
    <VCard
      class="forgot-password-card"
      max-width="450"
      elevation="2"
    >
      <VCardText class="pa-10">
        <h4 class="text-h4 mb-6 text-center">
          Forgot Password? 🔒
        </h4>
        <p class="text-center mb-6">
          Please contact your administrator at info@freynet-gagne.com to reset your password
        </p>

        <!-- Back to login -->
        <RouterLink
          class="d-flex align-center justify-center text-primary"
          :to="{ name: 'login' }"
        >
          <VIcon
            icon="bx-chevron-left"
            size="20"
            class="me-1 flip-in-rtl"
          />
          <span>Back to login</span>
        </RouterLink>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss" scoped>
.forgot-password-page {
  min-height: 100vh;
  background-color: #ffffff;
}

.forgot-password-card {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
}
</style>