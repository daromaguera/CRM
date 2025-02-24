import axios from 'axios'
import { ref } from 'vue'

export function useEmailVerification() {
  const email = ref('')
  const isLoading = ref(false)
  const error = ref(null)

  const sendResetLinkEmail = async () => {
    isLoading.value = true
    error.value = null
    try {
      const response = await axios.post('/api/auth/forgot-password', { email: email.value })
      return response.data
    } catch (err) {
      error.value = err.response.data
    } finally {
      isLoading.value = false
    }
  }

  return {
    email,
    isLoading,
    error,
    sendResetLinkEmail,
  }
}
