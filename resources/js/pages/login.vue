<script setup>
import { emailValidator, requiredValidator } from '@/@core/utils/validators'
import { useLogin } from '@/composables/auth/login'
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import authV2LoginIllustration from '@images/pages/auth-v2-login-illustration.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { ref } from 'vue'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const { formData, formAction, refVForm, onFormSubmit } = useLogin()

const isPasswordVisible = ref(false)
</script>

<template>
  <a href="javascript:void(0)">
    <div class="auth-logo d-flex align-center gap-x-2">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </a>

  <VRow no-gutters class="auth-wrapper bg-surface">
    <VCol md="8" class="d-none d-md-flex">
      <!-- illustration -->
      <div class="position-relative bg-background w-100 pa-8">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg
            max-width="700"
            height="80vh"
            :src="authV2LoginIllustration"
            class="auth-illustration"
          />
        </div>
      </div>
    </VCol>

    <VCol cols="12" md="4" class="auth-card-v2 d-flex align-center justify-center">
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-6">
        <VCardText>
          <h4 class="text-h4 mb-1 text-center">Welcome to Real D2D! 👋🏻</h4>
          <p class="mb-0 text-center">Please sign-in to your account and start the adventure!</p>
        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onFormSubmit">
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.email"
                  autofocus
                  label="Email"
                  :rules="[requiredValidator, emailValidator]"
                  type="email"
                  placeholder="johndoe@email.com"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.password"
                  label="Password"
                  placeholder="············"
                  :rules="[requiredValidator]"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div class="d-flex align-center flex-wrap justify-space-between my-6">
                  <VCheckbox v-model="formData.remember" label="Remember me" />
                  <RouterLink class="text-primary text-body-1" :to="{ name: 'forgot-password' }">
                    Forgot Password?
                  </RouterLink>
                </div>

                <VBtn
                  block
                  type="submit"
                  :disabled="formAction.formProcess"
                  :loading="formAction.formProcess"
                >
                  Login
                </VBtn>
              </VCol>
            </VRow>
          </VForm>

          <VRow>
            <!-- create account -->
            <VCol cols="12" class="text-body-1 text-center mt-5">
              <span class="d-inline-block"> New on our platform? </span>
              <RouterLink
                class="text-primary ms-1 d-inline-block text-body-1"
                to="/register/account"
              >
                Create an account
              </RouterLink>
            </VCol>

            <VCol cols="12" class="d-flex align-center">
              <VDivider />
              <span class="mx-4">or</span>
              <VDivider />
            </VCol>

            <!-- auth providers -->
            <VCol cols="12" class="text-center">
              <AuthProvider />
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use '@core-scss/template/pages/page-auth';
</style>
