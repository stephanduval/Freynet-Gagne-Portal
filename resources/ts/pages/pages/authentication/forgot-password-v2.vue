<script setup lang="ts">
import { useRouter } from 'vue-router'
import { VForm } from 'vuetify/components/VForm'
import { layoutConfig } from '@layouts'

definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})

const router = useRouter()
const email = ref('')
const loading = ref(false)
const error = ref('')
const success = ref('')
const refVForm = ref<VForm>()

const handleSubmit = async () => {
  try {
    loading.value = true
    error.value = ''
    success.value = ''

    const response = await fetch('/api/auth/forgot-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email: email.value }),
    })

    const data = await response.json()

    if (response.ok) {
      success.value = data.message

      // Optionally redirect after a delay
      setTimeout(() => {
        router.push({ name: 'login' })
      }, 3000)
    }
    else {
      error.value = data.message || 'An error occurred. Please try again.'
    }
  }
  catch (err) {
    error.value = 'An error occurred. Please try again.'
    console.error('Password reset request error:', err)
  }
  finally {
    loading.value = false
  }
}

const onSubmit = () => {
  refVForm.value?.validate()
    .then(({ valid: isValid }) => {
      if (isValid)
        handleSubmit()
    })
}
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
          Enter your email and we'll send you instructions to reset your password
        </p>

        <VForm
          ref="refVForm"
          @submit.prevent="onSubmit"
        >
          <VRow>
            <!-- Success Alert -->
            <VCol
              v-if="success"
              cols="12"
            >
              <VAlert
                color="success"
                variant="tonal"
                class="mb-4"
              >
                {{ success }}
              </VAlert>
            </VCol>

            <!-- Error Alert -->
            <VCol
              v-if="error"
              cols="12"
            >
              <VAlert
                color="error"
                variant="tonal"
                class="mb-4"
              >
                {{ error }}
              </VAlert>
            </VCol>

            <!-- email -->
            <VCol cols="12">
              <AppTextField
                v-model="email"
                autofocus
                label="Email"
                placeholder="johndoe@email.com"
                type="email"
                :disabled="loading"
                :rules="[requiredValidator, emailValidator]"
              />
            </VCol>

            <!-- Reset link -->
            <VCol cols="12">
              <VBtn
                block
                type="submit"
                :loading="loading"
                :disabled="loading"
              >
                Send Reset Link
              </VBtn>
            </VCol>

            <!-- back to login -->
            <VCol
              cols="12"
              class="text-center"
            >
              <RouterLink
                class="text-primary d-inline-flex align-center"
                :to="{ name: 'login' }"
              >
                <VIcon
                  icon="bx-chevron-left"
                  size="20"
                  class="me-1 flip-in-rtl"
                />
                <span>Back to login</span>
              </RouterLink>
            </VCol>
          </VRow>
        </VForm>
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