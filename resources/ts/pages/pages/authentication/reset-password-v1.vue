<script setup lang="ts">
import { VForm } from 'vuetify/components/VForm'
import { layoutConfig } from '@layouts'

definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})

const form = ref({
  newPassword: '',
  confirmPassword: '',
})

const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const refVForm = ref<VForm>()

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
  if (v !== form.value.newPassword)
    return 'Passwords must match'

  return true
}

const onSubmit = () => {
  refVForm.value?.validate()
    .then(({ valid: isValid }) => {
      if (isValid) {
        // Handle password reset logic here
        console.log('Password reset form submitted')
      }
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
            <!-- password -->
            <VCol cols="12">
              <AppTextField
                v-model="form.newPassword"
                autofocus
                label="New Password"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                :rules="[requiredValidator, passwordValidator]"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />
            </VCol>

            <!-- Confirm Password -->
            <VCol cols="12">
              <AppTextField
                v-model="form.confirmPassword"
                label="Confirm Password"
                placeholder="············"
                :type="isConfirmPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isConfirmPasswordVisible ? 'bx-hide' : 'bx-show'"
                :rules="[requiredValidator, passwordConfirmationValidator]"
                @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
              />
            </VCol>

            <!-- reset password -->
            <VCol cols="12">
              <VBtn
                block
                type="submit"
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
                :to="{ name: 'pages-authentication-login-v1' }"
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
.reset-password-page {
  min-height: 100vh;
  background-color: #ffffff;
}

.reset-password-card {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
}
</style>