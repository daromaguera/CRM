import { useBroadcastEvent } from '@/composables/broadcastEvent' // Import the broadcastEvent function
import { useAuthUserStore } from '@/stores/authUser'
import { formActionDefault } from '@/utils/constants'
import { useEventBus } from '@vueuse/core' // Import useEventBus
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

// by convention, composable function names start with "use"
export function useRegister() {
  // Auth Store
  const authUserStore = useAuthUserStore()

  // Router
  const router = useRouter()

  // Broadcast Event
  const { broadcastEvent, broadcastPublicEvent } = useBroadcastEvent() // Destructure the broadcastEvent and broadcastPublicEvent functions

  // Event Bus
  const { emit } = useEventBus('notification') // Destructure the emit function

  // state encapsulated and managed by the composable
  const formDataDefault = {
    firstname: '',
    lastname: '',
    email: '',
    company: '',
    password: '',
    password_confirmation: '',
    is_agree: false,
  }
  const formData = ref({ ...formDataDefault })
  const formAction = ref({ ...formActionDefault })
  const refVForm = ref()

  const onSubmit = async () => {
    // Reset Form Action utils; Turn on processing at the same time
    formAction.value = { ...formActionDefault, formProcess: true }

    const { accessToken, userData } = await $api('/auth/register', {
      method: 'POST',
      body: formData.value,
      onResponseError({ response }) {
        formAction.value.formStatus = response.status
        toast.error(response._data.message, toastOptions)

        onFormReset()
      },
    })

    // Add Success Message
    toast.success('Successfully Registered Account.', toastOptions)

    // Set Auth User
    authUserStore.setAuthUser({ userData, accessToken })

    //broadcast event
    await broadcastEvent(userData.id, 'User has registered an account')
    await broadcastPublicEvent('User has registered an account')
    emit('notification', { message: 'user has registered an account' })
    window.dispatchEvent(
      new CustomEvent('new-user', { detail: { message: 'user has registered an account' } })
    )

    // Redirect Acct to Email Verification Page
    router.replace('/register/email-confirmation')

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
