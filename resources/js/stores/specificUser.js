import axios from 'axios'
import { defineStore } from 'pinia'
import { useAuthUserStore } from './authUser'

export const useSpecificUserStore = defineStore('specificUser', {
  state: () => ({
    user: {
      avatarImg: null,
      firstName: '',
      lastName: '',
      username: '',
      email: '',
      phone: '',
      realtor_license_no: '',
      postal_zip: '',
      timezone: '',
      country: '',
      state_province: '',
      city_municipality: '',
      company: '',
      user_role_id: '',
    },
  }),
  actions: {
    async fetchUser(id) {
      const accessToken = localStorage.getItem('accessToken')
      const authUserStore = useAuthUserStore()
      const userId = id || authUserStore.authUser?.id

      try {
        const response = await axios.get(`/api/user/${userId}`, {
          headers: {
            Authorization: `Bearer ${accessToken}`,
          },
        })
        const data = response.data
        this.user = {
          avatarImg: data.image,
          firstName: data.firstname,
          lastName: data.lastname,
          username: data.username,
          email: data.email,
          phone: data.phone,
          realtor_license_no: data.realtor_license_no,
          postal_zip: data.postal_zip,
          timezone: data.timezone,
          country: data.country,
          state_province: data.state_province,
          city_municipality: data.city_municipality,
          company: data.company,
          user_role_id: data.user_role_id,
        }
      } catch (error) {
        console.error('Failed to fetch user:', error)
      }
    },
  },
})
