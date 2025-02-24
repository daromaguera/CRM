<script setup>
import axios from 'axios'
import { computed, onMounted, ref } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  badgeProps: {
    type: Object,
    required: false,
    default: undefined,
  },
  location: {
    type: null,
    required: false,
    default: 'bottom end',
  },
})

const emit = defineEmits(['read', 'unread', 'remove', 'click:notification'])

const notifications = ref([])

const isAllMarkRead = computed(() => {
  return notifications.value.some((item) => item.isSeen === false)
})

const markAllReadOrUnread = () => {
  const allNotificationsIds = notifications.value.map((item) => item.id)
  if (!isAllMarkRead.value) emit('unread', allNotificationsIds)
  else emit('read', allNotificationsIds)
}

const totalUnseenNotifications = computed(() => {
  return notifications.value.filter((item) => item.isSeen === false).length
})

const toggleReadUnread = async (isSeen, id) => {
  if (isSeen) {
    emit('unread', [id])
  } else {
    emit('read', [id])
    try {
      await axios.patch(`/api/notifications/${id}/seen`)
      const notification = notifications.value.find((notification) => notification.id === id)
      if (notification) {
        notification.isSeen = true
      }
    } catch (error) {
      console.error('Failed to mark notification as seen:', error)
    }
  }
}

const deleteNotification = async (id) => {
  try {
    await axios.delete(`/api/notifications/${id}`)
    notifications.value = notifications.value.filter((notification) => notification.id !== id)
  } catch (error) {
    console.error('Failed to delete notification:', error)
  }
}

const fetchNotifications = async (userId) => {
  try {
    const userNotifications = await axios.get(`/api/notifications/${userId}`)
    const publicNotifications = await axios.get('/api/public-notifications')
    notifications.value = [...userNotifications.data, ...publicNotifications.data]
  } catch (error) {
    console.error('Failed to fetch notifications:', error)
  }
}

// Function to reload the component
const reloadComponent = () => {
  const userData = localStorage.getItem('userData')
    ? JSON.parse(localStorage.getItem('userData'))
    : null
  const userId = userData?.id

  if (userId) {
    fetchNotifications(userId)
  }
}

onMounted(() => {
  reloadComponent()

  // Set interval to reload every 3 seconds
  setInterval(reloadComponent, 3000)

  // Listen for the custom event
  window.addEventListener('profile-updated', (e) => {
    notifications.value.push({
      id: Date.now(),
      title: 'Information Change',
      subtitle: e.detail.message,
      time: new Date().toLocaleTimeString(),
      isSeen: false,
    })
  })

  // Listen for the new user registration event
  window.addEventListener('new-user', (e) => {
    notifications.value.push({
      id: Date.now(),
      title: 'New User Registration',
      subtitle: e.detail.message,
      time: new Date().toLocaleTimeString(),
      isSeen: false,
    })
  })
})
</script>

<template>
  <IconBtn id="notification-btn">
    <VBadge
      v-bind="props.badgeProps"
      :model-value="notifications.some((n) => !n.isSeen)"
      color="error"
      dot
      offset-x="2"
      offset-y="3"
    >
      <VIcon size="22" icon="bx-bell" />
    </VBadge>

    <VMenu
      activator="parent"
      width="380px"
      :location="props.location"
      offset="21px"
      :close-on-content-click="false"
    >
      <VCard class="d-flex flex-column">
        <!-- 👉 Header -->
        <VCardItem class="notification-section">
          <VCardTitle class="text-h6"> Notifications </VCardTitle>

          <template #append>
            <VChip
              v-show="notifications.some((n) => !n.isSeen)"
              size="small"
              color="primary"
              class="me-2"
            >
              {{ totalUnseenNotifications }} New
            </VChip>
            <IconBtn v-show="notifications.length" size="34" @click="markAllReadOrUnread">
              <VIcon
                size="20"
                color="high-emphasis"
                :icon="!isAllMarkRead ? 'bx-envelope' : 'bx-envelope-open'"
              />

              <VTooltip activator="parent" location="start">
                {{ !isAllMarkRead ? 'Mark all as unread' : 'Mark all as read' }}
              </VTooltip>
            </IconBtn>
          </template>
        </VCardItem>

        <VDivider />

        <!-- 👉 Notifications list -->
        <PerfectScrollbar :options="{ wheelPropagation: false }" style="max-block-size: 23.75rem">
          <VList class="notification-list rounded-0 py-0">
            <template v-for="(notification, index) in notifications" :key="notification.title">
              <VDivider v-if="index > 0" />
              <VListItem
                link
                lines="one"
                min-height="66px"
                class="list-item-hover-class"
                @click.stop="toggleReadUnread(notification.isSeen, notification.id)"
              >
                <div class="d-flex gap-x-3">
                  <div>
                    <VAvatar
                      :color="
                        notification.color && !notification.img ? notification.color : undefined
                      "
                      :variant="notification.img ? undefined : 'tonal'"
                    >
                      <span v-if="notification.text">{{ avatarText(notification.text) }}</span>
                      <VImg v-if="notification.img" :src="notification.img" />
                      <VIcon v-if="notification.icon" :icon="notification.icon" />
                    </VAvatar>
                  </div>
                  <!-- Slot: Prepend -->
                  <!-- Handles Avatar: Image, Icon, Text -->
                  <div class="d-flex justify-space-between flex-grow-1">
                    <div>
                      <p class="text-sm font-weight-medium mb-1">
                        {{ notification.title }}
                      </p>
                      <p
                        class="text-caption text-medium-emphasis mb-2"
                        style="letter-spacing: 0.4px !important; line-height: 18px"
                      >
                        {{ notification.subtitle }}
                      </p>
                      <p
                        class="text-caption mb-0"
                        style="letter-spacing: 0.4px !important; line-height: 18px"
                      >
                        {{ notification.time }}
                      </p>
                    </div>
                    <div class="d-flex flex-column gap-2">
                      <VIcon
                        size="10"
                        icon="bx-bxs-circle"
                        :color="!notification.isSeen ? 'primary' : '#a8aaae'"
                        :class="`${notification.isSeen ? 'visible-in-hover' : ''}`"
                        class="align-self-end"
                      />
                      <VIcon
                        size="20"
                        icon="bx-x"
                        class="visible-in-hover"
                        @click.stop="deleteNotification(notification.id)"
                      />
                    </div>
                  </div>
                </div>
              </VListItem>
            </template>

            <VListItem
              v-show="!notifications.length"
              class="text-center text-medium-emphasis"
              style="block-size: 56px"
            >
              <VListItemTitle>No Notification Found!</VListItemTitle>
            </VListItem>
          </VList>
        </PerfectScrollbar>

        <VDivider />

        <!-- 👉 Footer -->
        <VCardText v-show="notifications.length" class="py-4 px-5">
          <VBtn block size="small"> View All Notifications </VBtn>
        </VCardText>
      </VCard>
    </VMenu>
  </IconBtn>
</template>

<style lang="scss">
.notification-section {
  padding-block: 0.75rem;
  padding-inline: 1rem;
}

.list-item-hover-class {
  .visible-in-hover {
    display: none;
  }

  &:hover {
    .visible-in-hover {
      display: block;
    }
  }
}

.notification-list.v-list {
  .v-list-item {
    border-radius: 0 !important;
    margin: 0 !important;
    padding-block: 0.75rem !important;
    padding-inline: 1rem !important;
  }
}

// Badge Style Override for Notification Badge
.notification-badge {
  .v-badge__badge {
    /* stylelint-disable-next-line liberty/use-logical-spec */
    min-width: 18px;
    padding: 0;
    block-size: 18px;
  }
}
</style>
