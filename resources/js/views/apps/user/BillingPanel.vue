<script setup>
const props = defineProps({
  billingDetails: {
    type: Object,
    required: true,
  },
})

const isUserInfoEditDialogVisible = ref(false)
const isUpgradePlanDialogVisible = ref(false)
</script>

<template>
  <VRow>
    <!-- SECTION User Details -->
    <VCol cols="12">
      <!-- <VCard v-if="props.userData"></VCard> -->
      <VCard class="rounded-xl">

        <VCardText>

          <!-- 👉 Details -->
          <VRow>
            <VCol cols="6">
              <h5 class="text-h5">
                Billing Details
              </h5>
            </VCol>
            <VCol cols="6 d-flex justify-end">
              <VBtn
                variant="elevated"
                @click="isUserInfoEditDialogVisible = true"
              >
                Update <VIcon icon="mdi-grease-pencil"></VIcon>
              </VBtn>
            </VCol>
          </VRow>

          <VDivider class="my-4" />

          <VRow>
            <VCol cols="6">
              <h6 class="text-h6">
                <div>Email:</div>
                <div class="d-inline-block text-body-1">
                  {{ props.billingDetails.companyEmail }}
                </div>
              </h6>
            </VCol>
            <VCol cols="6" style="text-align:right;">
              <h6 class="text-h6">
                <div>Subscription Status:</div>
                <VChip
                  label
                  size="small"
                  color="success"
                >
                  {{ props.billingDetails.subscriptionStatus }}
                </VChip>
                <!-- <div class="d-inline-block text-body-1">
                  {{ props.billingDetails.subscriptionStatus }}
                </div> -->
              </h6>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="6">
              <h6 class="text-h6">
                <div>Phone:</div>
                <div class="d-inline-block text-body-1">
                  {{ props.billingDetails.phone }}
                </div>
              </h6>
            </VCol>
            <VCol cols="6" style="text-align:right;">
              <h6 class="text-h6">
                <div>Renews On:</div>
                <div class="d-inline-block text-body-1">
                  {{ props.billingDetails.renewsOn }}
                </div>
              </h6>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="6">
              <h6 class="text-h6">
                <div>[Misc. Info]:</div>
                <div class="d-inline-block text-body-1">
                  {{ props.billingDetails.misc }}
                </div>
              </h6>
            </VCol>
            <VCol cols="6" style="text-align:right;">
              <h6 class="text-h6">
                <div>Customer Since:</div>
                <div class="d-inline-block text-body-1">
                  {{ props.billingDetails.customerSince }}
                </div>
              </h6>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="6">
              <h6 class="text-h6">
                <div>Billing Address:</div>
                <div class="d-inline-block text-body-1">
                  {{ props.billingDetails.billingAddress }}
                </div>
              </h6>
            </VCol>
            <VCol cols="6 d-flex justify-end">
              <VBtn
                variant="tonal"
                color="error"
              >
                Cancel Subscription
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>

      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>

  <!-- 👉 Edit user info dialog -->
  <UserInfoEditDialog
    v-model:isDialogVisible="isUserInfoEditDialogVisible"
    :user-data="props.userData"
  />

  <!-- 👉 Upgrade plan dialog -->
  <UserUpgradePlanDialog v-model:isDialogVisible="isUpgradePlanDialogVisible" />
</template>

<style lang="scss" scoped>
@use "@core-scss/template/mixins" as templateMixins;

.card-list {
  --v-card-list-gap: 0.5rem;
}

.current-plan {
  border: 2px solid rgb(var(--v-theme-primary));

  @include templateMixins.custom-elevation(var(--v-theme-primary), "sm");
}

.text-capitalize {
  text-transform: capitalize !important;
}
</style>
