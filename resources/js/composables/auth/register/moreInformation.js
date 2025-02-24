import { useAuthUserStore } from '@/stores/authUser'
import { formActionDefault } from '@/utils/constants'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

// by convention, composable function names start with "use"
export function useMoreInformation() {
  // Auth Store
  const authUserStore = useAuthUserStore()

  // Router
  const router = useRouter()

  // state encapsulated and managed by the composable
  const formDataDefault = {
    phone: '',
    realtor_license_no: '',
    state_province: null,
    city_municipality: '',
    timezone: 'America/New_York', // Default timezone -> America/New_York
  }
  const formData = ref({ ...formDataDefault })
  const formAction = ref({ ...formActionDefault })
  const refVForm = ref()
  // const timezone_list = [
  //   'America/New_York',
  //   'America/Chicago',
  //   'America/Denver',
  //   'America/Los_Angeles',
  //   'America/Phoenix',
  //   'America/Anchorage',
  //   'America/Honolulu',
  //   'Asia/Manila'
  // ]

  const onSubmit = async () => {
    // Reset Form Action utils; Turn on processing at the same time
    formAction.value = { ...formActionDefault, formProcess: true }

    formData.value = { ...formData.value, state_province: formData.value.state_province.state }

    const { accessToken, userData } = await $api('/auth/more-information', {
      method: 'PUT',
      body: formData.value,
      headers: authUserStore.authHeaders,
      onResponseError({ response }) {
        formAction.value.formStatus = response.status
        toast.error(response._data.message, toastOptions)

        onFormReset()
      },
    })

    // Add Success Message
    toast.success('Successfully Added More Information.', toastOptions)

    // Set Auth User
    authUserStore.setAuthUser({ userData })

    // Redirect Acct to Invite Team Members
    router.replace('/register/team-members')

    onFormReset()
  }

  // Validate Form and Submit
  const onFormSubmit = () => {
    refVForm.value?.validate().then(({ valid }) => {
      if (valid) onSubmit()
    })
  }

  const onFormReset = () => {
    // Reset Form
    refVForm.value?.reset()
    // Turn off processing
    formAction.value.formProcess = false
  }

  // Cancel More Details and Logout
  const onLogout = async () => {
    formAction.value = { ...formActionDefault, formProcess: true }

    await $api('/auth/logout', {
      method: 'GET',
      headers: authUserStore.authHeaders,
    })

    formAction.value.formProcess = false

    setTimeout(() => {
      authUserStore.unsetAuthUser()
    }, 1500)

    router.replace('/login')
  }

  // expose managed state as return value
  return { formData, formAction, refVForm, onFormSubmit, onLogout }
}
