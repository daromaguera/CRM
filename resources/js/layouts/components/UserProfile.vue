<script setup>
import { useUserProfile } from '@/composables/auth/dashboard/userProfile'
import { useAuthUserStore } from '@/stores/authUser'
import { useSpecificUserStore } from '@/stores/specificUser'
import avatar from '@images/avatars/avatar-0.png'

// Auth Store
const authUserStore = useAuthUserStore()
const specificUserStore = useSpecificUserStore()

onMounted(() => {
  specificUserStore.fetchUser(authUserStore.authUser?.id)
})

const getAvatarUrl = (avatarImg) =>
  avatarImg ? `${import.meta.env.VITE_API_BASE_URL}/${avatarImg}` : avatar

const userAvatar = computed(() => getAvatarUrl(specificUserStore.user.avatarImg))

setInterval(() => {
  specificUserStore.fetchUser(authUserStore.authUser?.id)
}, 1000)

const { formAction, onLogout } = useUserProfile()
</script>

<template>
  <VBadge dot bordered location="bottom right" offset-x="3" offset-y="3" color="success">
    <VAvatar class="cursor-pointer" color="primary" variant="tonal">
      <!-- Use dynamic userAvatar as src -->
      <VImg :src="userAvatar" />

      <!-- SECTION Menu -->
      <VMenu activator="parent" width="230" location="bottom end" offset="14px">
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge dot location="bottom right" offset-x="3" offset-y="3" color="success">
                  <VAvatar color="primary" variant="tonal">
                    <VImg :src="userAvatar" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ specificUserStore.user.firstName + ' ' + specificUserStore.user.lastName }}
            </VListItemTitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 Profile -->
          <VListItem to="/user/user-profile">
            <template #prepend>
              <VIcon class="me-2" icon="bx-user" size="22" />
            </template>
            <VListItemTitle>Profile</VListItemTitle>
          </VListItem>

          <!-- 👉 Settings -->
          <VListItem :to="{ name: 'settings-tab', params: { tab: 'account' } }">
            <template #prepend>
              <VIcon class="me-2" icon="bx-cog" size="22" />
            </template>
            <VListItemTitle>Settings</VListItemTitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem @click="onLogout">
            <template #prepend>
              <VIcon class="me-2" icon="bx-log-out" size="22" />
            </template>
            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
    </VAvatar>
  </VBadge>
</template>
