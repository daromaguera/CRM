import { useAuthUserStore } from '@/stores/authUser';

export function userList() {
    // Auth Store
    const authUserStore = useAuthUserStore()

    const listForCRM = async () => {
        try {
            const response = await $api('auth/users-list', {
                method: 'GET',
                headers: authUserStore.authHeaders,
            })
            authUserStore.setUserListCRM(response.data)
        } catch (error) {
            console.error('Error fetching user list:', error)
        }
    }

    return { listForCRM }
}
