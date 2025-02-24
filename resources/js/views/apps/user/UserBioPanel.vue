<script setup>
import { useAuthUserStore } from '@/stores/authUser'
import { useSpecificUserStore } from '@/stores/specificUser'
import { onMounted } from 'vue'

const userStore = useSpecificUserStore()
const authUserStore = useAuthUserStore()

onMounted(() => {
  if (authUserStore.authUser) {
    userStore.fetchUser(authUserStore.authUser.id)
  }
})
</script>

<template>
  <VRow>
    <!-- SECTION User Details -->
    <VCol cols="12">
      <!-- <VCard v-if="props.userData"></VCard> -->
      <VCard class="rounded-xl">
        <VCardText>
          <!-- 👉 Details -->
          <h5 class="text-h5">
            {{ userStore.user.firstName || 'N/A' }} {{ userStore.user.lastName || 'N/A' }}
          </h5>

          <VDivider class="my-4" />

          <!-- 👉 User Details list -->
          <VRow>
            <VCol cols="6">
              <h6 class="text-h6">
                <div>Email:</div>
                <div class="d-inline-block text-body-1">
                  {{ userStore.user.email || 'N/A' }}
                </div>
              </h6>
            </VCol>
            <VCol cols="6" style="text-align: right">
              <h6 class="text-h6">
                <div>Last Login:</div>
                <div class="d-inline-block text-body-1">
                  {{ userStore.user.lastLogin || 'N/A' }}
                </div>
              </h6>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="6">
              <h6 class="text-h6">
                <div>Phone:</div>
                <div class="d-inline-block text-body-1">
                  {{ userStore.user.phone || 'N/A' }}
                </div>
              </h6>
            </VCol>
            <VCol cols="6" style="text-align: right">
              <h6 class="text-h6">
                <div>Subscription Status:</div>
                <div class="d-inline-block text-body-1">
                  {{ userStore.user.subscriptionStatus || 'N/A' }}
                </div>
              </h6>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="6">
              <h6 class="text-h6">
                <div>[Misc. Info]:</div>
                <div class="d-inline-block text-body-1">
                  {{ userStore.user.misc || 'N/A' }}
                </div>
              </h6>
            </VCol>
            <VCol cols="6" style="text-align: right">
              <h6 class="text-h6">
                <div>Subscription Renewal:</div>
                <div class="d-inline-block text-body-1">
                  {{ userStore.user.subscriptionRenewal || 'N/A' }}
                </div>
              </h6>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="6">
              <h6 class="text-h6">
                <div>Billing Address:</div>
                <div class="d-inline-block text-body-1">
                  {{ userStore.user.city_municipality || 'N/A' }},
                  {{ userStore.user.state_province || 'N/A' }},
                  {{ userStore.user.country || 'N/A' }}
                </div>
              </h6>
            </VCol>
            <VCol cols="6" style="text-align: right">
              <h6 class="text-h6">
                <div>Subscription Start:</div>
                <div class="d-inline-block text-body-1">
                  {{ userStore.user.subscriptionStart || 'N/A' }}
                </div>
              </h6>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>
</template>

<style lang="scss" scoped>
@use '@core-scss/template/mixins' as templateMixins;

.card-list {
  --v-card-list-gap: 0.5rem;
}

.text-capitalize {
  text-transform: capitalize !important;
}
</style>
