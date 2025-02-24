<script setup>
import { useEmailConfirmation } from '@/composables/auth/register/emailConfirmation'
import { useAuthUserStore } from '@/stores/authUser'
import authV2RegisterIllustration from '@images/pages/auth-v2-register-illustration.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { ref } from 'vue'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const authUserStore = useAuthUserStore()

const { code, formAction, onFormSubmit, onLogout, onResend } = useEmailConfirmation()

const isCancelVerificationDialogVisible = ref(false)

// Array to hold each character in the code
const inputs = ref([])

// Function to move focus to the next input field
const moveToNext = (index) => {
  if (code.value[index].length === 1 && index < inputs.value.length - 1) {
    inputs.value[index + 1].focus()
  }
}

const handlePaste = (event) => {
  const pasteData = event.clipboardData.getData('Text')

  if (pasteData.length === code.value.length) {
    // Split the pasted text and set each input value
    for (let i = 0; i < pasteData.length; i++) {
      code.value[i] = pasteData[i]
    }
    // Focus on the last input field
    inputs.value[code.value.length - 1].focus()
    event.preventDefault()
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
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-6">
        <VCardText class="text-center">
          <h4 class="text-h4 mb-1">Enter Confirmation Code</h4>

          <v-card class="my-4 ma-1" color="secondary">
            <v-card-text class="text-high-emphasis text-caption font-weight-bold text-black">
              We've sent you an email with a confirmation code on your email
              <span class="font-weight-black text-decoration-underline">
                {{ authUserStore.authUser.email }} </span
              >. Please check your email inbox!
            </v-card-text>
          </v-card>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="onFormSubmit">
            <VRow class="d-flex justify-center items-center align-center">
              <div class="d-flex justify-center items-center" style="gap: 4px">
                <input
                  v-for="(digit, index) in code"
                  :key="index"
                  v-model="code[index]"
                  ref="inputs"
                  type="text"
                  class="input-field"
                  style="inline-size: 40px; text-align: center"
                  maxlength="1"
                  @input="moveToNext(index)"
                  @paste="handlePaste"
                />
              </div>

              <div class="d-flex justify-center align-center pb-4 text-caption">
                Didn't Received Code?
                <VBtn
                  class="text-caption text-primary"
                  variant="text"
                  @click="onResend"
                  :disabled="formAction.formProcess"
                  :loading="formAction.formProcess"
                >
                  Resend
                </VBtn>
              </div>
            </VRow>

            <VRow class="d-flex justify-center ma-1">
              <div class="d-flex justify-space-between align-center" style="inline-size: 100%">
                <VBtn
                  class="text-primary ms-1 d-inline-block"
                  variant="text"
                  @click="isCancelVerificationDialogVisible = true"
                  :disabled="formAction.formProcess"
                  :loading="formAction.formProcess"
                >
                  Back
                </VBtn>

                <VBtn
                  type="submit"
                  :disabled="formAction.formProcess"
                  :loading="formAction.formProcess"
                >
                  Next
                </VBtn>
              </div>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>

  <ConfirmDialog
    v-model:isDialogVisible="isCancelVerificationDialogVisible"
    cancel-msg="Continue Email Verification"
    cancel-title="Continue Verification"
    confirmation-question="Are you certain you want to cancel email verification? The account will remain verifiable by logging in again. You will be redirected to the login page. Please note: THIS ACTION CANNOT BE UNDONE."
    confirm-msg="The email verification is cancelled."
    confirm-title="Cancelled Email Verification"
    @confirm="onLogout"
  ></ConfirmDialog>
</template>

<style lang="scss" scoped>
@use '@core-scss/template/pages/page-auth';

.signup-container {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-block-start: -80px;
  min-block-size: 100vh;
}

.signup-card {
  padding: 2rem;
  border-radius: 10px;
  background-color: white;
  box-shadow: 1px 1px 12px 0 #b2f1f7;
  inline-size: 100%;
  max-inline-size: 400px;
  text-align: center;
}

.signup-title {
  color: #1a1a1a;
  font-size: 1.5rem;
  margin-block-end: 1.5rem;
}

.input-label {
  display: block;
  color: #555;
  font-size: 1rem;
  margin-block-end: 0.5rem;
  text-align: start;
}

.input-field {
  padding: 0.75rem;

  /* border: 2px solid #1c5c91; */
  border-radius: 8px;
  background: #ececec;
  color: #333;
  font-size: 1rem;
  inline-size: 100%;

  /* background-color: #cee8fd; */
  margin-block-end: 1rem;
  outline: none;
  transition: border-color 0.3s;
}

.input-field:focus {
  border: 2px solid #2f75af;
  background-color: #cee8fd;
}

.cst_Back {
  color: #39a7c9;
  font-size: 1rem;
}
</style>
