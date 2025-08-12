<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { VForm } from 'vuetify/components/VForm'
import { layoutConfig } from '@layouts'

// Password validation rules
const passwordValidator = (v: string) => {
  if (!v)
    return 'Password is required'
  if (v.length < 8)
    return 'Password must be at least 8 characters'

  return true
}

const passwordConfirmationValidator = (v: string) => {
  if (!v)
    return 'Password confirmation is required'
  if (v !== form.value.password)
    return 'Passwords must match'

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
const refVForm = ref<VForm>()

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
    }
    else {
      error.value = data.message || 'An error occurred. Please try again.'
    }
  }
  catch (err) {
    error.value = 'An error occurred. Please try again.'
    console.error('Password reset error:', err)
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
  <div class="reset-password-page d-flex align-center justify-center">
    <VCard
      class="reset-password-card"
      max-width="450"
      elevation="2"
    >
      <VCardText class="pa-10">
        <h4 class="text-h4 mb-6 text-center">
          Reset Password 🔒
        </h4>
        <p class="text-center mb-6">
          Your new password must be different from previously used passwords
        </p>

        <VForm
          ref="refVForm"
          @submit.prevent="onSubmit"
        >
          <VRow>
            <!-- Code Validation Error -->
            <VCol
              v-if="!isValidCode && error"
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
              v-if="error && isValidCode"
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
                  :disabled="loading"
                  :rules="[requiredValidator, passwordValidator]"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
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
                  :disabled="loading"
                  :rules="[requiredValidator, passwordConfirmationValidator]"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
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
            </template>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss" scoped>
.reset-password-page {
  min-height: 100vh;
  background-color: #ffffff;
}

.reset-password-card {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
}
</style>