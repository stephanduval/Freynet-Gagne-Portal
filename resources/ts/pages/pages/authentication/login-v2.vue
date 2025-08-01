<script setup lang="ts">
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import { themeConfig } from '@themeConfig'

definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const isPasswordVisible = ref(false)
</script>

<template>
  <div class="login-page d-flex align-center justify-center">
    <VCard
      class="login-card"
      max-width="450"
      elevation="2"
    >
      <VCardText class="pa-10">
        <h4 class="text-h4 mb-6 text-center">
          Welcome to <span class="text-capitalize">{{ themeConfig.app.title }}</span>!
        </h4>
        <VForm @submit.prevent="() => {}">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <AppTextField
                v-model="form.email"
                autofocus
                label="Email or Username"
                type="email"
                placeholder="johndoe@email.com"
              />
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <AppTextField
                v-model="form.password"
                label="Password"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />

              <div class="d-flex align-center flex-wrap justify-space-between my-6">
                <VCheckbox
                  v-model="form.remember"
                  label="Remember me"
                />
                <RouterLink
                  class="text-primary"
                  :to="{ name: 'pages-authentication-forgot-password-v2' }"
                >
                  Forgot Password?
                </RouterLink>
              </div>

              <VBtn
                block
                type="submit"
              >
                Login
              </VBtn>
            </VCol>

            <!-- create account -->
            <VCol
              cols="12"
              class="text-body-1 text-center"
            >
              <span class="d-inline-block">
                New on our platform?
              </span>
              <RouterLink
                class="text-primary ms-1 d-inline-block text-body-1"
                :to="{ name: 'pages-authentication-register-v2' }"
              >
                Create an account
              </RouterLink>
            </VCol>

            <VCol
              cols="12"
              class="d-flex align-center"
            >
              <VDivider />
              <span class="mx-4">or</span>
              <VDivider />
            </VCol>

            <!-- auth providers -->
            <VCol
              cols="12"
              class="text-center"
            >
              <AuthProvider />
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss" scoped>
.login-page {
  min-height: 100vh;
  background-color: #ffffff;
}

.login-card {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
}
</style>
