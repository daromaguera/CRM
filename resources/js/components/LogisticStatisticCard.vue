<script setup>
const props = defineProps({
  logisticData: {
    type: Array,
    required: true,
  },
  loadingLogistics: {
    type: Boolean,
    required: true,
  },
  userRoleId: {
    type: Number,
    required: true,
  },
  additionalLogisticData: {
    type: Array,
    required: true,
  }
});
</script>

<template>
  <VRow>
    <VCol v-for="(data, index) in logisticData" :key="index" cols="12" md="3" sm="6">
      <VCard
        class="logistics-card-statistics rounded-xl"
        :style="data.isHover ? `border-block-end-color: rgb(var(--v-theme-${data.color}))` : `border-block-end-color: rgba(var(--v-theme-${data.color}),0.38)`"
        @mouseenter="data.isHover = true"
        @mouseleave="data.isHover = false"
        :class="{ 'blurred-icon': loadingLogistics }"
      >
        <VCardText>
          <div class="text-body-1 mb-2">{{ data.title }}</div>
          <div class="d-flex align-center gap-x-4 mb-2">
            <VAvatar variant="tonal" size="40" :color="data.color" rounded>
              <VIcon :icon="data.icon" size="24"/>
            </VAvatar>
            <h4 class="text-h4">
              <VIcon v-if="loadingLogistics" class="custom-spin2" icon="mdi-loading" spin></VIcon>
              <span v-if="!loadingLogistics">{{ data.value }}</span>
            </h4>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
  <VRow v-if="Number(userRoleId) === 2">
    <VCol v-for="(data, index) in additionalLogisticData" :key="index" cols="12" md="3" sm="6">
      <VCard
        class="logistics-card-statistics cursor-pointer rounded-xl"
        :style="data.isHover ? `border-block-end-color: rgb(var(--v-theme-${data.color}))` : `border-block-end-color: rgba(var(--v-theme-${data.color}),0.38)`"
        @mouseenter="data.isHover = true"
        @mouseleave="data.isHover = false"
        :class="{ 'blurred-icon': loadingLogistics }"
      >
        <VCardText>
          <div class="d-flex align-center gap-x-4 mb-2">
            <VAvatar variant="tonal" size="40" :color="data.color" rounded>
              <VIcon :icon="data.icon" size="24"/>
            </VAvatar>
            <h4 class="text-h4">
              <VIcon v-if="loadingLogistics" class="custom-spin2" icon="mdi-loading" spin></VIcon>
              <span v-if="!loadingLogistics">{{ data.value }}</span>
            </h4>
          </div>
          <div class="text-body-1 mb-2">{{ data.title }}</div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
@use "@core-scss/base/mixins" as mixins;

.logistics-card-statistics {
  border-block-end-style: solid;
  border-block-end-width: 2px;

  &:hover {
    border-block-end-width: 3px;
    margin-block-end: -1px;

    @include mixins.elevation(8);

    transition: all 0.1s ease-out;
  }
}

.skin--bordered {
  .logistics-card-statistics {
    border-block-end-width: 2px;

    &:hover {
      border-block-end-width: 3px;
      margin-block-end: -2px;
      transition: all 0.1s ease-out;
    }
  }
}
.blurred-icon {
  filter: blur(2px);
}
.custom-spin2 {
  animation: spin 1s linear infinite;
}
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>
