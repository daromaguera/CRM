import { useAuthUserStore } from '@/stores/authUser'
import { formActionDefault } from '@/utils/constants'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'

// by convention, composable function names start with "use"
export function useTeamMembers() {
  // Auth Store
  const authUserStore = useAuthUserStore()
  // Router
  const router = useRouter()

  // state encapsulated and managed by the composable
  const formDataDefault = {
    email: '',
  }
  const formData = ref([{ ...formDataDefault }, { ...formDataDefault }, { ...formDataDefault }])
  const formAction = ref({ ...formActionDefault })
  const refVForm = ref()

  const onAddMember = () => {
    formData.value.push({ ...formDataDefault })
  }

  const onDecreaseMember = () => {
    formData.value.pop()
  }

  const onSubmit = async () => {
    // Reset Form Action utils; Turn on processing at the same time
    formAction.value = { ...formActionDefault, formProcess: true }

    await $api('/auth/team-members', {
      method: 'POST',
      body: { members: formData.value },
      headers: authUserStore.authHeaders,
      onResponseError({ response }) {
        formAction.value.formStatus = response.status
        toast.error(response._data.message, toastOptions)

        onFormReset()
      },
    })

    // Add Success Message
    toast.success('Successfully Invited Team Members.', toastOptions)

    // Redirect Dashboard
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
  return { formData, formAction, refVForm, onAddMember, onDecreaseMember, onFormSubmit }
}
