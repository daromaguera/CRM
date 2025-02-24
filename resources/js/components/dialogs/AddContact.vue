<script setup>
import { toast } from 'vue3-toastify'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  formData: {
    type: Object,
    required: true,
  },
  onSubmit: {  // Receive the function as a prop
    type: Function,
    required: true,
  },
  loadingSubmitContact: {
    type: Boolean,
    required: true
  }
})

const validateSubmission = () => {
  if(props.formData.fullname === '' || props.formData.address === '' || props.formData.phone === '' || props.formData.email === '' || props.formData.yearsInHome === '' || props.formData.possibleEquity === '' || props.formData.roughCreditScore === '' || props.formData.zillowEstimate === ''){
    toast.error('Please fill required fields with asterisk (*)', toastOptions)
    return
  }else{
    props.onSubmit()
  }
}

const emit = defineEmits([
  'submit',
  'update:isDialogVisible',
])

const isUseAsBillingAddress = ref(false)

const onCancel = () => {
  emit('update:isDialogVisible', false)
}
const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const emailValidator = (value) => {
  const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return pattern.test(value) || 'Please enter a valid email.';
};
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 800"
    :model-value="props.isDialogVisible"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard class="pa-sm-10 pa-2" :loading="props.loadingSubmitContact">
      <VCardText>
        <form @submit.prevent="validateSubmission">
            <!-- 👉 Title -->
            <h4 class="text-h4 text-center mb-2">
            Add Contact
          </h4>
          <p class="text-body-1 text-center mb-6">
            Add or Modify New and Existing Contact Information...
          </p>

          <VRow class="pb-5">
            <VCol cols="12" lg="6" md="6" sm="12" xs="12">
              <div class="pb-4">
                <AppTextField
                  v-model="formData.fullname"
                  label="Name *"
                  :rules="[requiredValidator]"
                  placeholder="John Doe"
                />
              </div>
              <div class="pb-4">
                <AppTextField
                  v-model="formData.address"
                  label="Address *"
                  :rules="[requiredValidator]"
                  placeholder="123 st. Colorado St. Ave 22451"
                />
              </div>
              <div class="pb-4">
                <AppTextField
                  v-model="formData.phone"
                  label="Phone *"
                  :rules="[requiredValidator]"
                  placeholder="(+24) 395 3951 44"
                />
              </div>
              <div class="pb-4">
                <AppTextField
                  v-model="formData.email"
                  label="Email *"
                  :rules="[requiredValidator, emailValidator]"
                  placeholder="e.g., john.doe@realtor.com.us"
                />
              </div>
            </VCol>
            <VCol cols="12" lg="6" md="6" sm="12" xs="12">
              <div class="pb-4">
                <AppTextField
                  v-model="formData.yearsInHome"
                  label="Years in Home *"
                  :rules="[requiredValidator]"
                  type="number"
                  placeholder="e.g, 15"
                />
              </div>
              <div class="pb-4">
                <AppTextField
                  v-model="formData.possibleEquity"
                  label="Possible Equity *"
                  prefix="$"
                  type="number"
                  placeholder="0"
                />
              </div>
              <div class="pb-4">
                <AppTextField
                  v-model="formData.roughCreditScore"
                  label="Rough Credit Score *"
                  :rules="[requiredValidator]"
                  type="number"
                  placeholder="0"
                />
              </div>
              <div class="pb-4">
                <AppTextField
                  v-model="formData.zillowEstimate"
                  label="Zillow Estimate *"
                  prefix="$"
                  type="number"
                  :rules="[requiredValidator]"
                  placeholder="0"
                />
              </div>
            </VCol>
          </VRow>
          <VDivider />
          <div class="pt-8 d-flex gap-4 float-right">
            <VBtn size="small" class="rounded-xl" variant="tonal" color="error" @click="onCancel">Cancel</VBtn>
            <VBtn type="submit" size="small" class="rounded-xl" :disabled="props.loadingSubmitContact">
              <span v-if="!props.loadingSubmitContact">Create</span>
              <span v-else>Please wait...</span>
            </VBtn>
          </div>
        </form>

      
      </VCardText>
    </VCard>
  </VDialog>
</template>
