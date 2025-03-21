<script setup lang="ts">
import type { RouteLocationRaw } from 'vue-router'
import { useRouter } from 'vue-router'
import { VForm } from 'vuetify/components/VForm'
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import authV2LoginIllustration from '@images/pages/auth-v2-login-illustration.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const isPasswordVisible = ref(false)

// const route = useRoute()
const router = useRouter()

// const allRoutes = router.getRoutes()

// console.log(allRoutes)

const ability = useAbility()

const errors = ref<Record<string, string | undefined>>({
  email: undefined,
  password: undefined,
  general: undefined,
})

const refVForm = ref<VForm>()

const credentials = ref({
  email: 'email',
  password: 'password',
})

const isAuthenticated = () => {
  const accessToken = document.cookie.split('; ').find(row => row.startsWith('accessToken='))

  return !!accessToken
}

if (isAuthenticated())
  router.replace({ name: 'dashboards-crm' }) // Redirect to home if already logged in

const rememberMe = ref(false)

const login = async () => {
  try {
    const res = await fetch('/api/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include', // Ensure credentials are included
      body: JSON.stringify({
        email: credentials.value.email,
        password: credentials.value.password,
        remember_me: rememberMe.value,
      }),
    })

    if (!res.ok) {
      const error = await res.json()

      errors.value.general = error.message || 'Login failed. Please try again.'

      return
    }

    const { accessToken, userData, abilityRules } = await res.json()

    console.log('Ability Rules received:', abilityRules)

    console.log('User Data:', userData) // Add this line to check the user data
    // Update ability
    ability.update(abilityRules.map(rule => ({
      action: rule.action.toLowerCase(),
      subject: rule.subject.toLowerCase(),
    })))

    // Set cookies BEFORE navigation
    const userDataCookie = useCookie('userData')
    const abilityCookie = useCookie('userAbilityRules')
    const tokenCookie = useCookie('accessToken')

    // Set localStorage BEFORE navigation
    localStorage.setItem('userData', JSON.stringify(userData))
    localStorage.setItem('abilityRules', JSON.stringify(abilityRules))
    localStorage.setItem('accessToken', accessToken.toString())

    // Ensure the values are strings or serialized properly
    userDataCookie.value = JSON.stringify(userData)
    abilityCookie.value = JSON.stringify(abilityRules)
    tokenCookie.value = accessToken.toString()

    console.log('Document.cookie from login.vue', document.cookie)

    // Redirect user
    const userRole = userData.role?.toLowerCase() || 'User'

    const targetRoute = userRole === 'admin'
      ? { name: 'sdtestpage' }
      : userRole === 'client'
        ? { name: 'sdtestpage' }
        : userRole === 'user'
          ? { name: 'dashboards-crm' }
          : userRole === 'manager'
            ? { name: 'sdtestpage' }
            : { name: 'dashboards-analytics' }

    router.replace(targetRoute as RouteLocationRaw)
  }
  catch (err) {
    console.error('login error', err)
    errors.value.general = 'An error occurred. Please try again.'
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
            :src="authV2LoginIllustration"
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
            Welcome to <span class="text-capitalize"> {{ themeConfig.app.title }} </span>! 👋🏻
          </h4>
          <p class="mb-0">
            Please sign-in to your account and start the adventure
          </p>
        </VCardText>
        <VCardText>
          <!-- General Error Alert -->
          <VAlert
            v-if="errors.general"
            :value="!!errors.general"
            color="error"
            variant="tonal"
            class="mb-4"
          >
            {{ errors.general }}
          </VAlert>

          <VAlert
            color="primary"
            variant="tonal"
          >
            <p class="text-sm mb-2">
              Admin Email: <strong>admin@demo.com</strong> / Pass: <strong>admin</strong>
            </p>
            <p class="text-sm mb-0">
              Client Email: <strong>client@demo.com</strong> / Pass: <strong>client</strong>
            </p>
          </VAlert>
        </VCardText>
        <VCardText>
          <VForm
            ref="refVForm"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="credentials.email"
                  label="Email"
                  placeholder="johndoe@email.com"
                  type="email"
                  autofocus
                  :rules="[requiredValidator, emailValidator]"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="credentials.password"
                  label="Password"
                  placeholder="············"
                  :rules="[requiredValidator]"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :error-messages="errors.password"
                  :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div class="d-flex align-center flex-wrap justify-space-between my-6">
                  <VCheckbox
                    v-model="rememberMe"
                    label="Remember me"
                  />
                  <RouterLink
                    class="text-primary"
                    :to="{ name: 'forgot-password' }"
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
                  :to="{ name: 'register' }"
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
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
