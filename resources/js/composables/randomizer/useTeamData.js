import avatar1 from '@images/avatars/avatar-1.png'
import axios from 'axios'
import { ref } from 'vue'

export function useTeamData() {
  const teamData = ref({
    tag: 'team',
    tableHeaders: [
      { title: 'NAME', key: 'name' },
      { title: 'EMAIL', key: 'email' },
      { title: 'ROLE (Permissions)', key: 'role' },
    ],
    data: [],
  })

  const roles = ['Manager', 'Admin', 'Guest']

  const fetchTeamData = async () => {
    const primaryApiCall = axios.get('https://randomuser.me/api/?results=30')
    const timeout = new Promise((_, reject) => setTimeout(() => reject(new Error('timeout')), 3000))

    try {
      const response = await Promise.race([primaryApiCall, timeout])
      teamData.value.data = response.data.results.map((user) => ({
        name: `${user.name.first} ${user.name.last}`,
        avatar: user.picture.thumbnail,
        email: user.email,
        role: roles[Math.floor(Math.random() * roles.length)],
      }))
    } catch (error) {
      console.log('Error fetching team data from primary API:', error)
      try {
        const backupResponse = await axios.get('https://fakerapi.it/api/v2/persons?_locale=fr_FR')
        teamData.value.data = backupResponse.data.data.map((user) => ({
          name: `${user.firstname} ${user.lastname}`,
          avatar: avatar1, // Faker API does not provide an avatar
          email: user.email,
          role: roles[Math.floor(Math.random() * roles.length)],
        }))
      } catch (backupError) {
        console.error('Error fetching team data from backup API:', backupError)
      }
    }
  }

  return { teamData, fetchTeamData }
}
