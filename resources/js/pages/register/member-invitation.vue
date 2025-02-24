<script setup>
import { useMemberInvitation } from '@/composables/auth/register/memberInvitation'
import states from '@/controls/states.json'
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

const { formData, formAction, refVForm, onFormSubmit } = useMemberInvitation()

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
          <h4 class="text-h4 text-center mb-1">You've Been Invited</h4>
          <div class="text-body-2 text-secondary text-center">
            You've been invited to join Real D2D
          </div>
        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onFormSubmit">
            <VRow>
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
                <VBtn class="text-primary ms-1 d-inline-block" variant="text" to="/">
                  Go to Login
                </VBtn>

                <VBtn
                  type="submit"
                  :disabled="formAction.formProcess"
                  :loading="formAction.formProcess"
                >
                  Join
                </VBtn>
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
