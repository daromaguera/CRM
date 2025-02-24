import { defineStore } from 'pinia'
import { useAuthUserStore } from './authUser'

export const useUsersStore = defineStore('users', () => {
  // Utilized auth store para magamit dri nga store
  const authUserStore = useAuthUserStore()

  // States
  const userListCRM = ref([]) // Added by Dexter

  // Getters
  const getUserListCRM = computed(() => userListCRM.value) // Mao ni sakto nga getter

  // Actions
  async function setUserListCRM() {
    const { data } = await $api('users', {
      method: 'GET',
      headers: authUserStore.authHeaders,
    })
    console.log("Getting users...")
    userListCRM.value = data
  }

  return { userListCRM, getUserListCRM, setUserListCRM }
})
