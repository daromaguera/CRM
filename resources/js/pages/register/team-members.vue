<script setup>
import { useTeamMembers } from '@/composables/auth/register/teamMembers'
import authV2RegisterIllustration from '@images/pages/auth-v2-register-illustration.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { VCol } from 'vuetify/components'
import { VForm } from 'vuetify/components/VForm'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const { formData, formAction, refVForm, onAddMember, onDecreaseMember, onFormSubmit } =
  useTeamMembers()
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
          <h4 class="text-h4 mb-1 text-center">Add Team Members</h4>

          <v-card class="mt-4" color="secondary">
            <v-card-text
              class="text-medium-emphasis text-caption font-weight-bold text-black text-center"
            >
              Add emails to invite your team!
            </v-card-text>
          </v-card>
        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onFormSubmit">
            <VRow>
              <VCol
                cols="12"
                :style="{
                  maxHeight: '50vh',
                  overflow: 'auto',
                }"
              >
                <VRow>
                  <VCol cols="12" v-for="(item, index) in formData" :key="index">
                    <AppTextField
                      v-model="item.email"
                      :autofocus="index === 0"
                      label="Email of the member"
                      :rules="[requiredValidator, emailValidator]"
                    />
                  </VCol>
                </VRow>
              </VCol>

              <VCol cols="12" class="d-flex justify-center">
                <VBtn variant="text" icon @click="onAddMember">
                  <VIcon icon="mdi-plus"></VIcon>
                </VBtn>

                <VBtn variant="text" icon @click="onDecreaseMember">
                  <VIcon icon="mdi-minus"></VIcon>
                </VBtn>
              </VCol>

              <div class="d-flex justify-space-between align-center mt-4" style="inline-size: 100%">
                <VBtn
                  class="text-primary ms-1 d-inline-block"
                  variant="text"
                  :disabled="formAction.formProcess"
                  :loading="formAction.formProcess"
                  to="/"
                >
                  Skip
                </VBtn>

                <VBtn
                  type="submit"
                  :disabled="formAction.formProcess"
                  :loading="formAction.formProcess"
                >
                  Finish
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
