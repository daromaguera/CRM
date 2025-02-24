<script setup>
import {
  confirmedValidator,
  emailValidator,
  passwordValidator,
  requiredValidator,
} from '@/@core/utils/validators'
import { useRegister } from '@/composables/auth/register/register'
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import authV2RegisterIllustration from '@images/pages/auth-v2-register-illustration.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { ref } from 'vue'
import { VForm } from 'vuetify/components/VForm'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const { formData, formAction, refVForm, onFormSubmit } = useRegister()

const isPasswordVisible = ref(false)
const isPasswordConfirmationVisible = ref(false)
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

  <VRow no-gutters class="auth-wrapper bg-surface">
    <VCol md="8" class="d-none d-md-flex">
      <div class="position-relative bg-background w-100 pa-8">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg
            max-width="700"
            height="80vh"
            :src="authV2RegisterIllustration"
            class="auth-illustration"
          />
        </div>
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="auth-card-v2 d-flex align-center justify-center"
      style="background-color: rgb(var(--v-theme-surface))"
    >
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-6 mb-8">
        <VCardText>
          <h4 class="text-h4 mb-1 text-center">Let's Get Started 🚀</h4>
          <!-- 
            <p class="mb-0 text-center">
            Make your app management easy and fun!
            </p> 
          -->
        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onFormSubmit">
            <VRow>
              <!-- First Name -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.firstname"
                  :rules="[requiredValidator]"
                  autofocus
                  label="First Name"
                  placeholder="John"
                />
              </VCol>

              <!-- Last Name -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.lastname"
                  :rules="[requiredValidator]"
                  autofocus
                  label="Last Name"
                  placeholder="Doe"
                />
              </VCol>

              <!-- Email -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.email"
                  prepend-inner-icon="mdi-email"
                  label="Email"
                  type="email"
                  :rules="[requiredValidator, emailValidator]"
                  placeholder="johndoe@email.com"
                />
              </VCol>

              <!-- Company -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.company"
                  :rules="[requiredValidator]"
                  label="Company"
                  placeholder="Company Name"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.password"
                  prepend-inner-icon="mdi-key"
                  :rules="[requiredValidator, passwordValidator]"
                  label="Password"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />
              </VCol>

              <!-- password confirmation -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.password_confirmation"
                  prepend-inner-icon="mdi-key"
                  :rules="[
                    requiredValidator,
                    confirmedValidator(formData.password_confirmation, formData.password),
                  ]"
                  label="Password Confirmation"
                  placeholder="············"
                  :type="isPasswordConfirmationVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordConfirmationVisible ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="
                    isPasswordConfirmationVisible = !isPasswordConfirmationVisible
                  "
                />
              </VCol>

              <VCol cols="12">
                <div class="d-flex align-center my-2 mb-4">
                  <VCheckbox v-model="formData.is_agree" :rules="[requiredValidator]" inline />

                  <VLabel for="privacy-policy" style="opacity: 1">
                    <span class="me-1 text-high-emphasis">I agree to</span>
                    <a href="javascript:void(0)" class="text-primary">Privacy Policy & Terms</a>
                  </VLabel>
                </div>

                <div class="d-flex justify-space-between align-center">
                  <RouterLink class="text-primary ms-1 d-inline-block" :to="{ name: 'login' }">
                    <span> Back </span>
                  </RouterLink>

                  <VBtn
                    type="submit"
                    :disabled="formAction.formProcess"
                    :loading="formAction.formProcess"
                  >
                    Next
                  </VBtn>
                </div>
              </VCol>
            </VRow>
          </VForm>

          <VRow>
            <!-- create account -->
            <VCol cols="12" class="text-center text-base mt-5">
              <span class="d-inline-block">Already have an account?</span>
              <RouterLink class="text-primary ms-1 d-inline-block" :to="{ name: 'login' }">
                Sign in instead
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
