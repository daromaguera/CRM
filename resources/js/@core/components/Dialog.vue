<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: false,
    default: true,
  },
  isLabel: {
    type: String,
    required: false,
    default: true,
  },
  isMessage: {
    type: String,
    required: false,
    default: true,
  },
})

const emit = defineEmits(
    [
    'update:modelValue',
    'confirm:modelValue',
   ]
)

const closeDialog = () => {
  emit('update:modelValue', false)
}
const confirmAction = () => {
    emit('confirm:modelValue', true) 
}
</script>

<template>
  <VDialog
    v-model="props.isDialogVisible"
    persistent
    class="v-dialog-sm"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="closeDialog" />

    <!-- Dialog Content -->
    <VCard :title="isLabel">
      <VCardText>
        {{ isMessage }}
      </VCardText>

      <VCardText class="d-flex justify-end gap-3 flex-wrap">
        <VBtn
          color="secondary"
          variant="tonal"
          @click="closeDialog"
        >
          No
        </VBtn>
        <VBtn @click="confirmAction">
          Yes
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>
