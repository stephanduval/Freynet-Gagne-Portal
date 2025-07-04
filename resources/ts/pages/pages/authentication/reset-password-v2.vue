<script setup lang="ts">
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { useRoute, useRouter } from 'vue-router'

import authV2ResetPasswordIllustration from '@images/pages/auth-v2-reset-password-illustration.png'

// Password validation rules
const passwordValidator = (v: string) => {
  if (!v) return 'Password is required'
  if (v.length < 8) return 'Password must be at least 8 characters'
  return true
}

const passwordConfirmationValidator = (v: string) => {
  if (!v) return 'Password confirmation is required'
  if (v !== form.value.password) return 'Passwords must match'
  return true
}

definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})

const router = useRouter()
const route = useRoute()
const loading = ref(false)
const error = ref('')
const success = ref('')
const isValidCode = ref(false)

// Validate reset code on component mount
onMounted(async () => {
  const code = route.query.code as string
  const email = route.query.email as string

  if (!code || !email) {
    error.value = 'Invalid or missing reset code. Please contact your administrator for a new reset code.'
    return
  }

  form.value.email = email
  form.value.reset_code = code
  isValidCode.value = true
})

const form = ref({
  email: '',
  reset_code: '',
  password: '',
  password_confirmation: '',
})

const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)

const handleSubmit = async () => {
  try {
    loading.value = true
    error.value = ''
    success.value = ''

    const response = await fetch('/api/auth/reset-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(form.value),
    })

    const data = await response.json()

    if (response.ok) {
      success.value = data.message || 'Password has been reset successfully. You can now login with your new password.'
      // Redirect to login after a delay
      setTimeout(() => {
        router.push({ name: 'login' })
      }, 3000)
    } else {
      error.value = data.message || 'An error occurred. Please try again.'
    }
  } catch (err) {
    error.value = 'An error occurred. Please try again.'
    console.error('Password reset error:', err)
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <RouterLink to="/">
    <div class="auth-logo d-flex align-center gap-x-2">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </RouterLink>

  <VRow
    no-gutters
    class="auth-wrapper bg-surface"
  >
    <VCol
      md="8"
      class="d-none d-md-flex"
    >
      <div class="position-relative bg-background w-100 pa-8">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg
            max-width="700"
            :src="authV2ResetPasswordIllustration"
            class="auth-illustration"
          />
        </div>
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="auth-card-v2 d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-6"
      >
        <VCardText>
          <h4 class="text-h4 mb-1">
            Reset Password 🔒
          </h4>
          <p class="mb-0">
            Your new password must be different from previously used passwords
          </p>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="handleSubmit">
            <VRow>
              <!-- Code Validation Error -->
              <VCol v-if="!isValidCode && error" cols="12">
                <VAlert
                  color="error"
                  variant="tonal"
                  class="mb-4"
                >
                  {{ error }}
                </VAlert>
              </VCol>

              <!-- Success Alert -->
              <VCol v-if="success" cols="12">
                <VAlert
                  color="success"
                  variant="tonal"
                  class="mb-4"
                >
                  {{ success }}
                </VAlert>
              </VCol>

              <!-- Error Alert -->
              <VCol v-if="error" cols="12">
                <VAlert
                  color="error"
                  variant="tonal"
                  class="mb-4"
                >
                  {{ error }}
                </VAlert>
              </VCol>

              <!-- Form Fields (only show if code is valid) -->
              <template v-if="isValidCode">
                <!-- Reset Code -->
                <VCol cols="12">
                  <AppTextField
                    v-model="form.reset_code"
                    label="Reset Code"
                    placeholder="Enter the reset code"
                    :disabled="true"
                  />
                </VCol>

                <!-- Email -->
                <VCol cols="12">
                  <AppTextField
                    v-model="form.email"
                    label="Email"
                    type="email"
                    :disabled="true"
                  />
                </VCol>

                <!-- password -->
                <VCol cols="12">
                  <AppTextField
                    v-model="form.password"
                    autofocus
                    label="New Password"
                    placeholder="············"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                    :disabled="loading"
                    :rules="[requiredValidator, passwordValidator]"
                  />
                </VCol>

                <!-- Confirm Password -->
                <VCol cols="12">
                  <AppTextField
                    v-model="form.password_confirmation"
                    label="Confirm Password"
                    placeholder="············"
                    :type="isConfirmPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isConfirmPasswordVisible ? 'bx-hide' : 'bx-show'"
                    @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                    :disabled="loading"
                    :rules="[requiredValidator, passwordConfirmationValidator]"
                  />
                </VCol>

                <!-- Set password -->
                <VCol cols="12">
                  <VBtn
                    block
                    type="submit"
                    :loading="loading"
                    :disabled="loading"
                  >
                    Save New Password
                  </VBtn>
                </VCol>

                <!-- back to login -->
                <VCol cols="12">
                  <RouterLink
                    class="d-flex align-center justify-center"
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
              </template>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";
</style>
