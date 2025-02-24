<script setup>
import { toast } from 'vue3-toastify'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { VForm } from 'vuetify/components/VForm'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  onSubmit: {  // Receive the function as a prop
    type: Function,
    required: true,
  },
  formData: {
    type: Object,
    required: true,
  },
  loadingSubmitNewDC: {
    type: Boolean,
    required: true
  },
  formKey: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits([
  'update:isDialogVisible',
])

const onCancel = () => {
  emit('update:isDialogVisible', false)
}
const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const scrollbarRef = ref(null);
const validateSubmission = () => {
  if(props.formData.dripName === '' || props.formData.description === '' || props.formData.triggerWords === '' || props.formData.firstMessage === '' || props.formData.time === ''){
    toast.error('Please fill required fields with asterisk (*)', toastOptions)
    return
  }else{
    // Reset the scrollbar position
    if (scrollbarRef.value) {
      scrollbarRef.value.$el.scrollTop = 0; // Reset scroll position
    }
    props.onSubmit()
  }
}
const refForm = ref()
</script>

<template>
  <VNavigationDrawer
    temporary
    location="end"
    :model-value="props.isDialogVisible"
    width="370"
    :border="0"
    class="scrollable-content"
    @update:model-value="dialogModelValueUpdate"
  >
    <PerfectScrollbar ref="scrollbarRef" :options="{ wheelPropagation: false }">
      <VCard class="pb-8" :loading="props.loadingSubmitNewDC">
        <VCardText>
          <VForm ref="refForm" :key="props.formKey" @submit.prevent="validateSubmission">
              <!-- 👉 Title -->
              <h4 class="text-h4 text-center mb-2">
              New Campaign
            </h4>
            <p class="text-body-1 text-center mb-6">
              Adding new drip campaign
            </p>

            <VRow class="pb-5">
              <VCol cols="12" lg="12" md="12" sm="12" xs="12">
                <div class="pb-4">
                  <AppTextField
                    v-model="formData.dripName"
                    label="Drip Name *"
                    :rules="[requiredValidator]"
                    placeholder="Name your drip"
                  />
                </div>
                <div class="pb-8">
                  <AppTextarea
                    v-model="formData.description" 
                    label="Description *"
                    :rules="[requiredValidator]"
                    placeholder="Type the description of drip campaign..."
                  />
                </div>
                <VDivider />
                <div class="pb-8 mt-8">
                  <AppTextarea
                    v-model="formData.triggerWords" 
                    label="Trigger Words *"
                    :rules="[requiredValidator]"
                    placeholder="Type the triggered words for this drip campaign..."
                  />
                </div>
                <VDivider />
                <h3 class="pb-4 mt-8">Start of Campaign</h3>
                <div class="pb-4">
                  <label>Message *</label>
                  <TiptapEditor
                    v-model="formData.firstMessage"
                    class="border rounded basic-editor"
                  />
                </div>
                <div class="pb-4">
                  <AppDateTimePicker
                    v-model="formData.time"
                    label="Time to send *"
                    placeholder="Select time"
                    :config="{ enableTime: true, noCalendar: true, dateFormat: 'h:i K', altInput: true, altFormat: 'h:i K', time_24hr: false }"
                    :rules="[requiredValidator]"
                    attach="body"
                    persistent retain-focus
                    class="allow-interaction"
                  />
                </div>
              </VCol>
            </VRow>
            <div class="pt-10 d-flex gap-4">
              <VBtn type="submit"  :disabled="props.loadingSubmitNewDC">
                <span v-if="!props.loadingSubmitNewDC">Create</span>
                <span v-else>Please wait...</span>
              </VBtn>
              <VBtn variant="outlined" color="secondary" @click="onCancel">Cancel</VBtn>
            </div>
          </VForm>

        
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>

<style lang="scss" scoped>
.allow-interaction {
  pointer-events: auto !important;
}
</style>

