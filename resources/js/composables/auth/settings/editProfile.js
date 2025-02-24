import axios from 'axios'
import { ref } from 'vue'

export function useEditProfile() {
  const isLoading = ref(false)
  const error = ref(null)

  const accessToken = localStorage.getItem('accessToken')

  const updateProfileAttribute = async (userId, attribute, value) => {
    const endpoints = {
      firstname: `/api/user/update/firstname/${userId}`,
      lastname: `/api/user/update/lastname/${userId}`,
      username: `/api/user/update/username/${userId}`,
      phone: `/api/user/update/phone/${userId}`,
      email: `/api/user/update/email/${userId}`,
      password: `/api/user/update/password/${userId}`,
      realtor_license_no: `/api/user/update/realtor_license_no/${userId}`,
      postal_zip: `/api/user/update/postal_zip/${userId}`,
      country: `/api/user/update/country/${userId}`,
      state_province: `/api/user/update/state_province/${userId}`,
      city_municipality: `/api/user/update/city_municipality/${userId}`,
    }

    const url = endpoints[attribute]
    if (!url) {
      throw new Error(`Invalid attribute: ${attribute}`)
    }

    isLoading.value = true
    error.value = null
    try {
      await axios.put(
        url,
        { [attribute]: value },
        {
          headers: {
            Authorization: `Bearer ${accessToken}`,
            'Content-Type': 'application/json',
          },
        }
      )
    } catch (err) {
      error.value = err
    } finally {
      isLoading.value = false
    }
  }

  return {
    isLoading,
    error,
    updateProfileAttribute,
  }
}
