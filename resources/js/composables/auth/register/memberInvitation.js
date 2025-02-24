import { formActionDefault } from '@/utils/constants'

// by convention, composable function names start with "use"
export function useMemberInvitation() {
  // Router
  const route = useRoute()
  const router = useRouter()

  // state encapsulated and managed by the composable
  const formDataDefault = {
    email: '',
    password: '',
    password_confirmation: '',
    realtor_license_no: '',
    state_province: null,
    city_municipality: '',
  }
  const formData = ref({ ...formDataDefault })
  const formAction = ref({ ...formActionDefault })
  const refVForm = ref()
  const tokenId = ref()

  const onSubmit = async () => {
    // Reset Form Action utils; Turn on processing at the same time
    formAction.value = { ...formActionDefault, formProcess: true }

    formData.value = { ...formData.value, state_province: formData.value.state_province.state }

    const { accessToken, userData } = await $api('/auth/member-invitation', {
      method: 'POST',
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
    router.replace('/')

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

  onMounted(() => {
    formData.value.email = route.query.email
    tokenId.value = route.query.token
  })

  // expose managed state as return value
  return { formData, formAction, refVForm, onFormSubmit }
}
