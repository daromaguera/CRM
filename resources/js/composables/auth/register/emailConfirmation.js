import { useAuthUserStore } from '@/stores/authUser'
import { formActionDefault } from '@/utils/constants'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

// by convention, composable function names start with "use"
export function useEmailConfirmation() {
  // Auth Store
  const authUserStore = useAuthUserStore()

  // Router
  const router = useRouter()

  // state encapsulated and managed by the composable
  const code = ref(['', '', '', '', '', ''])
  const formAction = ref({ ...formActionDefault })

  const onSubmit = async () => {
    // Reset Form Action utils; Turn on processing at the same time
    formAction.value = { ...formActionDefault, formProcess: true }

    if (code.value.join('').length < 6) {
      toast.error('Code must be atleast 6 digits.', toastOptions)

      onFormReset()

      return
    }

    const { userData } = await $api('/auth/code/verification', {
      method: 'POST',
      body: {
        code: code.value.join(''),
      },
      headers: authUserStore.authHeaders,
      onResponseError({ response }) {
        formAction.value.formStatus = response.status
        if (response.status === 422) toast.error('Verification Code is incorrect.', toastOptions)
        else toast.error('Verification Failed. Please try again.', toastOptions)

        onFormReset()
      },
    })

    // Add Success Message
    toast.success('Successfully Verified Email.', toastOptions)

    // Set Auth User
    authUserStore.setAuthUser({ userData })

    // Redirect Acct to More Information
    router.replace('/register/more-information')

    onFormReset()
  }

  // Validate Form and Submit
  const onFormSubmit = () => {
    onSubmit()
  }

  const onFormReset = () => {
    code.value = ['', '', '', '', '', '']
    // Turn off processing
    formAction.value.formProcess = false
  }

  // Cancel Verification and Logout
  const onLogout = async () => {
    formAction.value = { ...formActionDefault, formProcess: true }

    await $api('/auth/logout', {
      method: 'GET',
      headers: authUserStore.authHeaders,
    })

    toast.warn('Cancelled Email Verification.', toastOptions)

    formAction.value.formProcess = false

    setTimeout(() => {
      authUserStore.unsetAuthUser()
    }, 1500)

    router.replace('/login')
  }

  // Resend Verification Code
  const onResend = async () => {
    formAction.value = { ...formActionDefault, formProcess: true }

    await $api('/auth/email/verification-notification', {
      method: 'POST',
      headers: authUserStore.authHeaders,
    })

    toast.success('Resent Verification Code.', toastOptions)

    formAction.value.formProcess = false
  }

  // expose managed state as return value
  return { code, formAction, onFormSubmit, onLogout, onResend }
}
