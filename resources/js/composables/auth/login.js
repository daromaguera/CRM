import { useAuthUserStore } from '@/stores/authUser'
import { formActionDefault } from '@/utils/constants'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

// by convention, composable function names start with "use"
export function useLogin() {
  // Auth Store
  const authUserStore = useAuthUserStore()

  // Router
  const router = useRouter()

  // state encapsulated and managed by the composable
  const formDataDefault = {
    email: '',
    password: '',
    remember: false,
  }
  const formData = ref({ ...formDataDefault })
  const formAction = ref({ ...formActionDefault })
  const refVForm = ref()

  const onSubmit = async () => {
    // Reset Form Action utils; Turn on processing at the same time
    formAction.value = { ...formActionDefault, formProcess: true }

    const { accessToken, userData } = await $api('/auth/login', {
      method: 'POST',
      body: formData.value,
      onResponseError({ response }) {
        formAction.value.formStatus = response.status
        toast.error('Username or Password is incorrect.', toastOptions)

        onFormReset()
      },
    })

    // Add Success Message
    toast.success('Successfully Logged Account.', toastOptions)

    // Set Auth User
    authUserStore.setAuthUser({ userData, accessToken })

    // Redirect Acct to Dashboard
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

  // expose managed state as return value
  return { formData, formAction, refVForm, onFormSubmit }
}
