<script setup>
import { useEditProfile } from '@/composables/auth/settings/editProfile' // Import the new composable
import { useImageUpdate } from '@/composables/auth/settings/uploadProfileImage'
import { useSpecificUserStore } from '@/stores/specificUser'
import avatar from '@images/avatars/avatar-0.png'
import { onMounted, ref, watch } from 'vue'
import { toast } from 'vue3-toastify'

const specificUserStore = useSpecificUserStore()
const refInputEl = ref()
const isConfirmDialogOpen = ref(false)
const isAccountDeactivated = ref(false)
const validateAccountDeactivation = [(v) => !!v || 'Please confirm account deactivation']
const authUser = ref(
  localStorage.getItem('userData') ? JSON.parse(localStorage.getItem('userData')) : null
)
const { broadcastEvent } = useBroadcastEvent()
const imageFile = ref(null)
const { updateImage, error: imageError, isLoading: isImageLoading } = useImageUpdate()
const { updateProfileAttribute, isLoading: isProfileLoading } = useEditProfile() // Destructure the new composable
const emit = defineEmits(['notification'])
let isFormReset = false

const BASE_URL = import.meta.env.VITE_API_BASE_URL

const getAvatarUrl = (avatarImg) => (avatarImg ? `${BASE_URL}/${avatarImg}` : avatar)

const accountData = ref({
  avatarImg: getAvatarUrl(specificUserStore.user.avatarImg),
  firstName: specificUserStore.user.firstName,
  lastName: specificUserStore.user.lastName,
  username: specificUserStore.user.username,
  email: specificUserStore.user.email,
  phone: specificUserStore.user.phone,
  realtor_license_no: specificUserStore.user.realtor_license_no,
  postal_zip: specificUserStore.user.postal_zip,
  country: specificUserStore.user.country,
  state_province: specificUserStore.user.state_province,
  city_municipality: specificUserStore.user.city_municipality,
  company: specificUserStore.user.company,
})

const reloadAccountData = () => {
  accountData.value = {
    avatarImg: getAvatarUrl(specificUserStore.user.avatarImg),
    firstName: specificUserStore.user.firstName,
    lastName: specificUserStore.user.lastName,
    username: specificUserStore.user.username,
    email: specificUserStore.user.email,
    phone: specificUserStore.user.phone,
    realtor_license_no: specificUserStore.user.realtor_license_no,
    postal_zip: specificUserStore.user.postal_zip,
    country: specificUserStore.user.country,
    state_province: specificUserStore.user.state_province,
    city_municipality: specificUserStore.user.city_municipality,
    /* company: specificUserStore.user.company, */
  }
}

const fetchUserData = async () => {
  const userData = localStorage.getItem('userData')
    ? JSON.parse(localStorage.getItem('userData'))
    : null
  const userId = userData?.id

  if (userId) {
    await specificUserStore.fetchUser(userId)
    reloadAccountData()
    resetForm()
  }
}

onMounted(() => {
  fetchUserData()
  /* setInterval(fetchUserData, 2000) */ // SPA realtime update
})

const accountDataLocal = ref(JSON.parse(JSON.stringify(accountData.value)))

const resetForm = () => {
  if (!isFormReset) {
    accountDataLocal.value = JSON.parse(JSON.stringify(accountData.value))
    isFormReset = true
  }
}

const changeAvatar = (file) => {
  const fileReader = new FileReader()
  const { files } = file.target
  if (files && files.length) {
    fileReader.readAsDataURL(files[0])
    fileReader.onload = () => {
      if (typeof fileReader.result === 'string')
        accountDataLocal.value.avatarImg = fileReader.result
    }
  }
}

const resetAvatar = () => {
  accountDataLocal.value.avatarImg = accountData.value.avatarImg
}

watch(
  () => accountDataLocal.value.avatarImg,
  (newAvatarImg) => {
    specificUserStore.user.avatarImg = newAvatarImg
  }
)

const onFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    imageFile.value = file
  }
}

const saveChanges = async () => {
  try {
    const userId = authUser.value?.id
    if (!userId) throw new Error('User ID not found in local storage.')

    if (imageFile.value) {
      const formData = new FormData()
      formData.append('image', imageFile.value)
      await updateImage(userId, formData)
      specificUserStore.user.avatarImg = accountDataLocal.value.avatarImg
    }

    const validAttributes = {
      firstname: accountDataLocal.value.firstName,
      lastname: accountDataLocal.value.lastName,
      username: accountDataLocal.value.username,
      email: accountDataLocal.value.email,
      phone: accountDataLocal.value.phone,
      realtor_license_no: accountDataLocal.value.realtor_license_no,
      postal_zip: accountDataLocal.value.postal_zip,
      country: accountDataLocal.value.country,
      state_province: accountDataLocal.value.state_province,
      city_municipality: accountDataLocal.value.city_municipality,
    }

    for (const [attribute, value] of Object.entries(validAttributes)) {
      if (value !== undefined && value !== null) {
        await updateProfileAttribute(userId, attribute, value)
      }
    }
  } catch (err) {
    console.error('Failed to save changes:', err)
  }
  toast.success('Profile updated successfully', toastOptions)
  const userId = authUser.value?.id
  await broadcastEvent(userId, 'Profile updated successfully')
  emit('notification', { message: 'Profile updated successfully' })
  window.dispatchEvent(
    new CustomEvent('profile-updated', { detail: { message: 'Profile updated successfully' } })
  )
}

const resetAddressPart = (part, index) => {
  const addressParts = accountData.value.address.split(',').map((part) => part.trim())
  accountDataLocal.value.address = addressParts.join(', ')
}

const timezones = [
  '(GMT-11:00) International Date Line West',
  '(GMT-11:00) Midway Island',
  '(GMT-10:00) Hawaii',
  '(GMT-09:00) Alaska',
  '(GMT-08:00) Pacific Time (US & Canada)',
  '(GMT-08:00) Tijuana',
  '(GMT-07:00) Arizona',
  '(GMT-07:00) Chihuahua',
  '(GMT-07:00) La Paz',
  '(GMT-07:00) Mazatlan',
  '(GMT-07:00) Mountain Time (US & Canada)',
  '(GMT-06:00) Central America',
  '(GMT-06:00) Central Time (US & Canada)',
  '(GMT-06:00) Guadalajara',
  '(GMT-06:00) Mexico City',
  '(GMT-06:00) Monterrey',
  '(GMT-06:00) Saskatchewan',
  '(GMT-05:00) Bogota',
  '(GMT-05:00) Eastern Time (US & Canada)',
  '(GMT-05:00) Indiana (East)',
  '(GMT-05:00) Lima',
  '(GMT-05:00) Quito',
  '(GMT-04:00) Atlantic Time (Canada)',
  '(GMT-04:00) Caracas',
  '(GMT-04:00) La Paz',
  '(GMT-04:00) Santiago',
  '(GMT-03:30) Newfoundland',
  '(GMT-03:00) Brasilia',
  '(GMT-03:00) Buenos Aires',
  '(GMT-03:00) Georgetown',
  '(GMT-03:00) Greenland',
  '(GMT-02:00) Mid-Atlantic',
  '(GMT-01:00) Azores',
  '(GMT-01:00) Cape Verde Is.',
  '(GMT+00:00) Casablanca',
  '(GMT+00:00) Dublin',
  '(GMT+00:00) Edinburgh',
  '(GMT+00:00) Lisbon',
  '(GMT+00:00) London',
]

const currencies = [
  'USD',
  'EUR',
  'GBP',
  'AUD',
  'BRL',
  'CAD',
  'CNY',
  'CZK',
  'DKK',
  'HKD',
  'HUF',
  'INR',
]
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard class="rounded-xl">
        <VCardText class="pb-md-10">
          <div class="d-flex mb-4">
            <!-- 👉 Avatar -->
            <VAvatar rounded size="100" class="me-6" :image="accountDataLocal.avatarImg" />
            <!-- 👉 Upload Photo -->
            <form class="d-flex flex-column justify-center gap-4">
              <div class="d-flex flex-wrap gap-4">
                <VBtn color="primary" @click="refInputEl?.click()">
                  <VIcon icon="bx-cloud-upload" class="d-sm-none" />
                  <span class="d-none d-sm-block">Upload new photo</span>
                </VBtn>
                <input
                  ref="refInputEl"
                  type="file"
                  name="file"
                  accept=".jpeg,.png,.jpg,GIF"
                  hidden
                  @input="changeAvatar"
                  @change="onFileChange"
                />
                <VBtn type="reset" color="secondary" variant="tonal" @click="resetAvatar">
                  <span class="d-none d-sm-block">Reset</span>
                  <VIcon icon="bx-refresh" class="d-sm-none" />
                </VBtn>
              </div>
              <p class="text-body-1 mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
            </form>
          </div>
          <VDivider />
        </VCardText>

        <VCardText>
          <!-- 👉 Form -->
          <VForm>
            <VRow>
              <!-- 👉 First Name -->
              <VCol md="6" cols="12">
                <AppTextField
                  v-model="accountDataLocal.firstName"
                  :placeholder="specificUserStore.user.firstName || 'John'"
                  label="First Name"
                />
              </VCol>

              <!-- 👉 Last Name -->
              <VCol md="6" cols="12">
                <AppTextField
                  v-model="accountDataLocal.lastName"
                  :placeholder="specificUserStore.user.lastName || 'Doe'"
                  label="Last Name"
                />
              </VCol>

              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.username"
                  :placeholder="specificUserStore.user.username || 'GuestUser'"
                  label="User Name"
                />
              </VCol>

              <!-- 👉 Email -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.email"
                  :placeholder="specificUserStore.user.email || 'johndoe@gmail.com'"
                  label="E-mail"
                  type="email"
                />
              </VCol>

              <!-- 👉 Phone -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.phone"
                  :placeholder="specificUserStore.user.phone || '+1 (917) 543-9876'"
                  label="Phone Number"
                />
              </VCol>

              <!-- 👉 Organization -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.realtor_license_no"
                  :placeholder="specificUserStore.user.realtor_license_no || '123456-ZA-00'"
                  label="Realtor License Number"
                />
              </VCol>

              <v-divider class="my-4 mx-4"></v-divider>

              <!-- 👉 Address -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.postal_zip"
                  :placeholder="specificUserStore.user.postal_zip || '12345'"
                  label="Postal Zip"
                />
              </VCol>
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.country"
                  :placeholder="specificUserStore.user.country || 'USA'"
                  label="Country"
                />
              </VCol>
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.state_province"
                  :placeholder="specificUserStore.user.state_province || 'California'"
                  label="State/Province"
                />
              </VCol>
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.city_municipality"
                  :placeholder="specificUserStore.user.city_municipality || 'Los Angeles'"
                  label="City/Municipality"
                />
              </VCol>

              <!-- 👉 Company -->
              <!--  <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.company"
                  :placeholder="specificUserStore.user.company || 'Company Name'"
                  label="Company"
                />
              </VCol> -->

              <!-- 👉 Form Actions -->
              <VCol cols="12" class="d-flex flex-wrap gap-4 mt-4">
                <VBtn
                  :loading="isImageLoading || isProfileLoading"
                  color="primary"
                  @click.prevent="saveChanges"
                >
                  Save changes
                </VBtn>
                <VBtn color="secondary" variant="tonal" type="reset" @click.prevent="resetForm">
                  Cancel
                </VBtn>
                <router-link to="/user/user-profile">
                  <VBtn color="secondary">View Profile</VBtn>
                </router-link>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="12">
      <!-- 👉 Delete Account -->
      <VCard title="Delete Account" class="rounded-xl">
        <VCardText>
          <!-- 👉 Checkbox and Button  -->
          <div>
            <VCheckbox
              v-model="isAccountDeactivated"
              :rules="validateAccountDeactivation"
              label="I confirm my account deactivation"
            />
          </div>

          <VBtn
            :disabled="!isAccountDeactivated"
            color="error"
            class="mt-6"
            @click="isConfirmDialogOpen = true"
          >
            Deactivate Account
          </VBtn>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>

  <!-- Confirm Dialog -->
  <ConfirmDialog
    v-model:isDialogVisible="isConfirmDialogOpen"
    confirmation-question="Are you sure you want to deactivate your account?"
    confirm-title="Deactivated!"
    confirm-msg="Your account has been deactivated successfully."
    cancel-title="Cancelled"
    cancel-msg="Account Deactivation Cancelled!"
  />
</template>
