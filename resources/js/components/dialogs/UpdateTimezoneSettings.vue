<script setup>
import { useAdjustTimezoneStore } from '@/composables/auth/user_settings/AdjustTimezone'
import { toast } from 'vue3-toastify'
import { useRouter } from 'vue-router'

const router = useRouter()

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  user_default_timezone: {
    type: null,
    required: true,
  }
})

const emit = defineEmits([
  'submit',
  'update:isDialogVisible',
])

const store = useAdjustTimezoneStore()

const selected_newTimeZone = ref(props.user_default_timezone)
const clickSubmit = ref(true)

watch(selected_newTimeZone, val => {
  if (val !== props.user_default_timezone){
    clickSubmit.value = false
  }else{
    clickSubmit.value = true
  }
})

const onCancel = () => {
  emit('update:isDialogVisible', false)
}

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const timezone_list = [
  'America/New_York',
  'America/Chicago',
  'America/Denver',
  'America/Los_Angeles',
  'America/Phoenix',
  'America/Anchorage',
  'America/Honolulu',
]

const refForm = ref()
const handleSubmit = async () => {
  const { valid } = await refForm.value?.validate();
  if (valid) {
    let userData = JSON.parse(localStorage.getItem('userData'));
    const result = await store.updateTimezone(selected_newTimeZone.value)
    userData.timezone = result;
    localStorage.setItem('userData', JSON.stringify(userData));
    toast.success('Your Timezone has now set to ' + result, toastOptions)
    window.location.reload();
    //emit('update:isDialogVisible', false)
  }else{
    alert("Error: Invalid data")
  }
}
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 800"
    :model-value="props.isDialogVisible"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard class="pa-sm-10 pa-2">
      <VCardText>
        <!-- 👉 Title -->
        <h4 class="text-h4 text-center mb-2">
          TIMEZONE
        </h4>
        <p class="text-body-1 text-center mb-6">
          The Calendar is currently displaying appointments based on the your current Timezone. You can adjust your timezone based on your location. This will help you to manage your appointments or events more effectively.
        </p>
        <VForm
            ref="refForm"
            @submit.prevent="handleSubmit"
          >

          <VRow class="pb-5">
            <VCol cols="12">
              <span class="float-right"><small><span style="color:#949292;">Your Current Timezone: <b>{{ props.user_default_timezone }}</b></span> </small></span><br/>
              <AppSelect
                v-model="selected_newTimeZone"
                :items="timezone_list"
                append-icon="mdi-timetable"
                label="Select Other Timezones : "
                single-line
                variant="filled"
                placeholder="Click here"
              />
            </VCol>
          </VRow>
          <VDivider />
          <div class="pt-8 d-flex gap-4 float-right">
            <VBtn size="small" class="rounded-xl" variant="tonal" color="error" @click="onCancel">Cancel</VBtn>
            <VBtn type="submit" size="small" class="rounded-xl custom-btn" :disabled="clickSubmit">Save</VBtn>
          </div>

        </VForm>

      </VCardText>
    </VCard>
  </VDialog>
</template>

<style lang="scss" scoped>
.custom-btn:disabled {
  background-color: #4a4949 !important;
  color: #aeaeae !important;
}
</style>
