<script setup lang="ts">
import type { RouteLocationRaw } from 'vue-router'
import { useRouter } from 'vue-router'
import { VForm } from 'vuetify/components/VForm'
import { $api, clearAuthData, setAuthData } from '@/utils/api'
import { layoutConfig } from '@layouts'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const isPasswordVisible = ref(false)
const router = useRouter()
const ability = useAbility()

const errors = ref<Record<string, string | undefined>>({
  email: undefined,
  password: undefined,
  general: undefined,
})

const refVForm = ref<VForm>()

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const isAuthenticated = () => {
  const accessToken = document.cookie.split('; ').find(row => row.startsWith('accessToken='))

  return !!accessToken
}

if (isAuthenticated())
  router.replace({ name: 'dashboards-analytics' })

const login = async () => {
  try {
    const { accessToken, userData, abilityRules } = await $api('/auth/login', {
      method: 'POST',
      body: {
        email: form.value.email,
        password: form.value.password,
        remember_me: form.value.remember,
      },
    })

    // Add debugging
    // console.log('Login Response:', {
    //   accessToken: accessToken ? 'Token received' : 'No token',
    //   userData: userData ? 'User data received' : 'No user data',
    //   abilityRules: abilityRules ? `${abilityRules.length} rules received` : 'No rules',
    // })

    if (!accessToken || !userData || !abilityRules)
      throw new Error('Invalid login response - missing required data')

    // Update ability with debugging
    const mappedRules = abilityRules.map((rule: { action: string; subject: string }) => {
      // console.log('Mapping rule:', { original: rule, mapped: mappedRule })
      return {
        action: rule.action.toLowerCase(),
        subject: rule.subject.toLowerCase(),
      }
    })

    // console.log('Mapped Ability Rules:', mappedRules)

    ability.update(mappedRules)

    // Verify ability was updated
    // console.log('Current Ability Rules:', ability.rules)

    // Use the enhanced setAuthData utility
    try {
      setAuthData({ accessToken, userData, abilityRules })

      // console.log('Auth data stored successfully')
    }
    catch (error) {
      console.error('Failed to store auth data:', error)
      throw new Error('Failed to store authentication data')
    }

    // --- Direct Role-Based Redirect ---
    const userRole = userData.role?.toLowerCase() || 'User'
    let targetRoute: RouteLocationRaw

    // console.log(`[DEBUG] Determining redirect target for role: ${userRole}`);

    if (userRole === 'admin' || userRole === 'auth') {
      targetRoute = { name: 'dashboards-analytics' }

      // console.log(`[DEBUG] Target set for admin/auth:`, targetRoute);
    }
    else if (userRole === 'client') {
      targetRoute = { name: 'apps-email' }

      // console.log(`[DEBUG] Target set for client:`, targetRoute);
    }
    else if (userRole === 'manager' || userRole === 'user') {
      targetRoute = { path: '/messages/list' }

      // console.log(`[DEBUG] Target set for manager/user:`, targetRoute);
    }
    else {
      // console.warn(`[DEBUG] Unexpected role "${userRole}", defaulting to dashboards-analytics`);
      targetRoute = { name: 'dashboards-analytics' }
    }

    // console.log(`[DEBUG] Attempting router.replace with:`, targetRoute);
    await router.replace(targetRoute)
  }
  catch (err: any) {
    console.error('Login error:', err)
    errors.value.general = err.data?.message || err.message || 'An error occurred during login. Please try again.'

    // Clear any partial auth data on error
    clearAuthData()
  }
}

const onSubmit = () => {
  refVForm.value?.validate()
    .then(({ valid: isValid }) => {
      if (isValid)
        login()
    })
}
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
          Welcome to <span class="text-capitalize">{{ layoutConfig.app.title }}</span>!
        </h4>

        <VForm
          ref="refVForm"
          @submit.prevent="onSubmit"
        >
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <AppTextField
                v-model="form.email"
                autofocus
                label="Email or Username"
                type="email"
                placeholder="johndoe@email.com"
                :rules="[requiredValidator, emailValidator]"
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
                :rules="[requiredValidator]"
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
