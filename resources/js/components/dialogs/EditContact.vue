<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import {
  useCRM,
} from '@/composables/auth/crm/useCRM'
import { toast } from 'vue3-toastify'
import { useRouter } from 'vue-router'

const router = useRouter()
const { editContact } = useCRM()

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  contactID: {
    type: Number,
    required: true,
  },
  userName: {
    type: String,
    required: true,
  },
  addr: {
    type: String,
  },
  phone: {
    type: String,
  },
  email: {
    type: String,
  },
  years_in_home: {
    type: Number,
  },
  possible_equity: {
    type: Number,
  },
  rough_credit_score: {
    type: Number,
  },
  zillow_estimate: {
    type: Number,
  },
  userType: {
    type: Number,
    required: true
  }
})

const emit = defineEmits([
  'submit',
  'update:isDialogVisible',
])

const formData = ref({
  id: props.contactID ?? '',
  fullname: props.userName ?? '',
  address: props.addr ?? '',
  phone: props.phone ?? '',
  email: props.email ?? '',
  yearsInHome: props.years_in_home ?? '',
  possibleEquity: props.possible_equity ?? '',
  roughCreditScore: props.rough_credit_score ?? '',
  zillowEstimate: props.zillow_estimate ?? ''
});

const onCancel = () => {
  emit('update:isDialogVisible', false)
}

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const currentTab = ref('user')
const tabData = computed(() => {
  const data = {
    user: {
      tag: 'user',
      tableHeaders: [
        {
          title: 'NAME',
          key: 'name_user',
        },
        {
          title: 'EMAIL',
          key: 'email_user',
        },
        {
          title: 'Last Login',
          key: 'lastLogin_user',
        },
        {
          title: 'User Actions',
          key: 'userActions_user',
        },
      ],
      data: [
        {
          avatar: avatar1,
          userFullName: 'Albert Flores',
          email: 'af@kw.com',
          lastLogin: '8/10/2024',
        },
        {
          avatar: avatar1,
          userFullName: 'Albert Flores',
          email: 'af@kw.com',
          lastLogin: '8/10/2024',
        },
        {
          avatar: avatar1,
          userFullName: 'Albert Flores',
          email: 'af@kw.com',
          lastLogin: '8/10/2024',
        },
      ]
    },
  }
  
  return data[currentTab.value]
})

const loadingSubmitEditForm = ref(false)
const onFormSubmit = async () => {
  loadingSubmitEditForm.value = true;
  const {data, total, error, logistics, resultEditedContact} = await editContact(formData.value, 1, 10, props.userType)
  console.log("Result Edited: ", resultEditedContact)
  if(!error) {
    toast.success('Contact has been successfully updated.', toastOptions)
    setTimeout(() => {
      emit('update:isDialogVisible', false)
      router.push('/crm/contact-details/'+resultEditedContact.id+'?id='+resultEditedContact.id+'&name='+resultEditedContact.name+'&timeOnDoors=661&doorsKnocked=323&appointmentsSet=143&contacts=244&addr='+resultEditedContact.address+'&phone='+resultEditedContact.phone+'&email='+resultEditedContact.email+'&years_in_home='+resultEditedContact.years_in_home+'&possible_equity='+resultEditedContact.possible_equity+'&rough_credit_score='+resultEditedContact.rough_credit_score+'&zillow_estimate='+resultEditedContact.zillow_estimate+'')
    }, 1000);
  }else{
    toast.error('An Error occured. Please consult the administrator or try again later.', toastOptions)
  }
  loadingSubmitEditForm.value = false;
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

    <VCard class="pa-sm-10 pa-2" :loading="loadingSubmitEditForm">
      <VCardText>
        <!-- 👉 Title -->
        <h4 class="text-h4 text-center mb-2">
          Edit Contact
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
                :rules="[requiredValidator]"
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
                placeholder="e.g, 15"
              />
            </div>
            <div class="pb-4">
              <AppTextField
                v-model="formData.possibleEquity"
                label="Possible Equity *"
                :rules="[requiredValidator]"
                placeholder="..."
              />
            </div>
            <div class="pb-4">
              <AppTextField
                v-model="formData.roughCreditScore"
                label="Rough Credit Score *"
                :rules="[requiredValidator]"
                placeholder="..."
              />
            </div>
            <div class="pb-4">
              <AppTextField
                v-model="formData.zillowEstimate"
                label="Zillow Estimate *"
                :rules="[requiredValidator]"
                placeholder="..."
              />
            </div>
          </VCol>
        </VRow>
        <VDivider />
        <div class="pt-8 d-flex gap-4 float-right">
          <VBtn size="small" class="rounded-xl" variant="tonal" color="error" @click="onCancel">Cancel</VBtn>
          <VBtn size="small" class="rounded-xl" @click="onFormSubmit" :disabled="loadingSubmitEditForm">
            <span v-if="!loadingSubmitEditForm">Save Changes</span>
            <span v-else>Please wait...</span>
          </VBtn>
        </div>

      </VCardText>
    </VCard>
  </VDialog>
</template>
