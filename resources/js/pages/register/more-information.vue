<script setup>
import { useMoreInformation } from '@/composables/auth/register/moreInformation'
import states from '@/controls/states.json'
import { useAuthUserStore } from '@/stores/authUser'
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

const authUserStore = useAuthUserStore()

const { formData, formAction, refVForm, onFormSubmit, onLogout } = useMoreInformation()

const isCancelMoreInformationDialogVisible = ref(false)
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
          <h4 class="text-h4 text-center mb-1">Welcome, {{ authUserStore.authUser.company }}</h4>

          <v-card class="mt-4" color="secondary">
            <v-card-text
              class="text-high-emphasis text-caption font-weight-bold text-black text-center"
            >
              Congratulations! We would like to ask few more information before we proceed further.
              Thank you.
            </v-card-text>
          </v-card>
        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onFormSubmit">
            <VRow>
              <!-- Phone -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.phone"
                  label="Phone"
                  :rules="[requiredValidator]"
                  autofocus
                />
              </VCol>

              <!-- Realtor License Number -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.realtor_license_no"
                  label="Realtor License Number"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- State -->
              <VCol cols="12">
                <label class="text-caption text-high-emphasis">State</label>
                <VAutocomplete
                  v-model="formData.state_province"
                  :hint="
                    formData.state_province
                      ? `${formData.state_province.state}, ${formData.state_province.abbr}`
                      : ''
                  "
                  :items="states"
                  item-title="state"
                  item-value="abbr"
                  :rules="[requiredValidator]"
                  persistent-hint
                  return-object
                ></VAutocomplete>
              </VCol>

              <!-- City -->
              <VCol cols="12">
                <AppTextField
                  v-model="formData.city_municipality"
                  label="City"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <div class="d-flex justify-space-between align-center mt-4" style="inline-size: 100%">
                <VBtn
                  class="text-primary ms-1 d-inline-block"
                  variant="text"
                  @click="isCancelMoreInformationDialogVisible = true"
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
    v-model:isDialogVisible="isCancelMoreInformationDialogVisible"
    cancel-msg="Continue Additional Account Information"
    cancel-title="Continue Registration"
    confirmation-question="Are you sure you want to stop entering additional account information? You can continue providing details for account creation by logging in again. By clicking CONFIRM, you will be redirected to the login page. Please note: THIS ACTION CANNOT BE UNDONE."
    confirm-msg="Additional account information entry has been canceled."
    confirm-title="Additional Information Input Canceled"
    @confirm="onLogout"
  ></ConfirmDialog>
</template>

<style lang="scss">
@use '@core-scss/template/pages/page-auth';
</style>
