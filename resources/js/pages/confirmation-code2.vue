<script setup>
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

// Array to hold each character in the code
const code = ref(['', '', '', '', '', ''])
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
          <!-- 
            <p class="mb-0">
            Make your app management easy and fun!
            </p> 
          -->
          <v-card class="my-4 ma-1" style="background: #5ba389">
            <v-card-text
              class="text-medium-emphasis text-caption"
              style="font-weight: bold; color: white !important"
            >
              We've sent you an email!
            </v-card-text>
          </v-card>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="() => {}">
            <VRow class="d-flex justify-center items-center align-center">
              <div class="d-flex justify-center items-center" style="gap: 4px">
                <input
                  v-for="(digit, index) in code"
                  :key="index"
                  v-model="code[index]"
                  ref="inputs"
                  type="text"
                  class="input-field"
                  style="width: 40px; text-align: center"
                  maxlength="1"
                  @input="moveToNext(index)"
                  @paste="handlePaste"
                />
              </div>

              <div class="d-flex justify-center items-center pb-4" style="font-size: 12px">
                Didn't Received Code? <span style="color: #2f737a; margin-left: 5px">Resend</span>
              </div>
            </VRow>
            <VRow class="d-flex justify-center ma-1">
              <div class="d-flex justify-space-between align-center" style="width: 100%">
                <RouterLink class="text-primary ms-1 d-inline-block" :to="{ name: 'login' }">
                  <span> Back </span>
                </RouterLink>
                <RouterLink class="text-primary ms-1 d-inline-block" :to="{ name: 'new-password' }">
                  <VBtn block> Next </VBtn>
                </RouterLink>
              </div>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
@use '@core-scss/template/pages/page-auth.scss';

.signup-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  margin-top: -80px;
}

.signup-card {
  background-color: white;
  padding: 2rem;
  max-width: 400px;
  width: 100%;
  border-radius: 10px;
  box-shadow: 1px 1px 12px 0px #b2f1f7;
  text-align: center;
}

.signup-title {
  font-size: 1.5rem;
  color: #1a1a1a;
  margin-bottom: 1.5rem;
}

.input-label {
  display: block;
  font-size: 1rem;
  color: #555;
  text-align: left;
  margin-bottom: 0.5rem;
}

.input-field {
  width: 100%;
  padding: 0.75rem;
  font-size: 1rem;
  color: #333;
  background: #ececec;
  /* border: 2px solid #1c5c91; */
  border-radius: 8px;
  /* background-color: #cee8fd; */
  margin-bottom: 1rem;
  outline: none;
  transition: border-color 0.3s;
}

.input-field:focus {
  border: 2px solid #2f75af;
  background-color: #cee8fd;
}

.signup-button {
  width: 80px;
  padding: 8px;
  font-size: 1rem;
  font-weight: bold;
  color: white;
  background-color: #5682d4;
  border: none;
  border-radius: 24px;
  cursor: pointer;
  transition: background-color 0.3s;
  margin-top: 10px;
}
.signup-button {
  width: 80px;
  padding: 8px;
  font-size: 1rem;
  font-weight: bold;
  color: white;
  background-color: #39a7c9;
  border: none;
  border-radius: 24px;
  cursor: pointer;
  transition: background-color 0.3s;
  margin-top: 10px;
}
.signup-button:hover {
  background-color: #3a65b6;
}
.signup-button:hover {
  background-color: #2784a0;
}
.cst_Back {
  font-size: 1rem;
  color: #39a7c9;
}
</style>
