<script setup>
import { VForm } from 'vuetify/components/VForm'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import authV2RegisterIllustration from '@images/pages/auth-v2-register-illustration.png'

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})
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
      style="background-color: rgb(var(--v-theme-surface));"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0"
      >
        <VCardText class="text-center">
          <span style="font-size:35px;">👋🏻</span>
          <h4 class="text-h4 mb-1">
            Saved!
          </h4>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="() => {}">
            <VRow>

              <VCol cols="12">

                <v-card
                  class="my-4 ma-1"
                  style="background:#5ba389;"
                >
                  <v-card-text class="text-medium-emphasis text-caption" style="font-weight: bold;color:white !important;">
                    Your PASSWORD has been updated!
                  </v-card-text>
                </v-card>

              </VCol>

            </VRow>
            <VRow class="d-flex justify-center ma-1 mt-10">
              <RouterLink
                class="text-primary ms-1 d-inline-block"
                :to="{ name: 'login' }"
              >
                <VBtn
                  block
                >
                  Login Now
                </VBtn>
              </RouterLink>
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
