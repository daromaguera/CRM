import { $api } from '@/utils/api'
import { ref } from 'vue'

export function useImageUpdate() {
  const isLoading = ref(false)
  const error = ref(null)
  const accessToken = localStorage.getItem('accessToken')

  const updateImage = async (id, formData) => {
    isLoading.value = true
    error.value = null

    try {
      const response = await $api(`/api/user/image/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
          Authorization: `Bearer ${accessToken}`,
        },
      })

      if (!response.ok) {
        throw new Error('Network response was not ok')
      }

      const data = await response.json()
      return data
    } catch (err) {
      error.value = err.message
      throw error.value
    } finally {
      isLoading.value = false
    }
  }

  return {
    isLoading,
    error,
    updateImage,
  }
}
