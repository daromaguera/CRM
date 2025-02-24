import { useAuthUserStore } from '@/stores/authUser'
import { formActionDefault } from '@/utils/constants'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

// by convention, composable function names start with "use"
export function useUserProfile() {
  // Auth Store
  const authUserStore = useAuthUserStore()

  // Router
  const router = useRouter()

  // state encapsulated and managed by the composable
  const formAction = ref({ ...formActionDefault })

  // Cancel More Details and Logout
  const onLogout = async () => {
    formAction.value = { ...formActionDefault, formProcess: true }

    await $api('/auth/logout', {
      method: 'GET',
      headers: authUserStore.authHeaders,
      onResponseError({ response }) {
        router.replace('/login')
      },
    })

    formAction.value.formProcess = false

    setTimeout(() => {
      authUserStore.unsetAuthUser()
    }, 1500)

    router.replace('/login')
  }

  // expose managed state as return value
  return { formAction, onLogout }
}
