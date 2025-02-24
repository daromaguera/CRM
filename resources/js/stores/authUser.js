import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

export const useAuthUserStore = defineStore('authUser', () => {
  // States
  const authUser = ref(
    localStorage.getItem('userData') ? JSON.parse(localStorage.getItem('userData')) : null
  )
  const authToken = ref(
    localStorage.getItem('accessToken') ? localStorage.getItem('accessToken') : null
  )

  // Getters
  const authHeaders = computed(() => {
    return {
      Authorization: `Bearer ${authToken.value}`,
    }
  })

  function isAuthenticated() {
    return !!authUser.value && !!authToken.value
  }

  function setAuthUser({ userData, accessToken }) {
    // useCookie('userAbilityRules').value = userAbilityRules
    // ability.update(userAbilityRules)
    localStorage.setItem('userData', JSON.stringify(userData))
    authUser.value = userData

    if (accessToken) {
      localStorage.setItem('accessToken', accessToken)
      authToken.value = accessToken
    }
  }

  // Unsetters
  function unsetAuthUser() {
    localStorage.removeItem('userData')
    localStorage.removeItem('accessToken')

    authUser.value = null
    authToken.value = null
  }

  return { authUser, authHeaders, isAuthenticated, setAuthUser, unsetAuthUser }
})
