<script setup>
import { router } from '@/plugins/1.router'
import authV2RegisterIllustration from '@images/pages/auth-v2-register-illustration.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import axios from 'axios'
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { toast } from 'vue3-toastify'
import { VForm } from 'vuetify/components/VForm'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const route = useRoute()
const token = route.params.token
const email = route.params.email
const baseUrl = ref(route.query.baseUrl || '')

const password = ref('')
const rePassword = ref('')

const passVisibility = ref(false)
const rePassVisibility = ref(false)

const resetPassword = async () => {
  console.log(email)
  try {
    const response = await axios.post('/api/auth/reset-password', {
      token: token,
      email: email,
      password: password.value,
      password_confirmation: rePassword.value,
    })

    if (response.status === 200) {
      toast.success('Password reset successfully!', toastOptions)
      router.push('/pass-has-changed')
    } else {
      toast.error('Error: ' + response.data.message, toastOptions)
    }
  } catch (error) {
    toast.error('Error: ' + error.response.data.message, toastOptions)
  }
}
</script>

<template>
  <RouterLink :to="baseUrl">
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
      <VCard flat :max-width="500" class="mt-12 mt-sm-0">
        <VCardText class="text-center">
          <h4 class="text-h4 mb-1">Setup a New Password</h4>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="resetPassword">
            <VRow>
              <VCol cols="12">
                <AppTextField
                  v-model="password"
                  label="New Password"
                  placeholder="Enter your new password"
                  :type="passVisibility ? 'text' : 'password'"
                  :append-inner-icon="passVisibility ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="passVisibility = !passVisibility"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="rePassword"
                  label="Confirm New Password"
                  placeholder="Re-type new password"
                  :type="rePassVisibility ? 'text' : 'password'"
                  :append-inner-icon="rePassVisibility ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="rePassVisibility = !rePassVisibility"
                />
              </VCol>
            </VRow>
            <VRow class="d-flex justify-center ma-1 mt-10">
              <div class="d-flex justify-space-between align-center" style="inline-size: 100%">
                <RouterLink class="text-primary ms-1 d-inline-block" to="/forgot-password">
                  <span> Back </span>
                </RouterLink>
                <VBtn block type="submit"> Next </VBtn>
              </div>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use '@core-scss/template/pages/page-auth';
</style>
