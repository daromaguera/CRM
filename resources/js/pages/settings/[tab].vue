<script setup>
import AccountSettingsAccount from '@/views/pages/account-settings/AccountSettings.vue'
import AccountSettingsSecurity from '@/views/pages/account-settings/AccountSettingsSecurity.vue'
import AccountSettingsBillingAndPlans from '@/views/pages/account-settings/AccountSettingsBillingAndPlans.vue'
import AccountSettingsNotification from '@/views/pages/account-settings/AccountSettingsNotification.vue'
import AccountSettingsConnections from '@/views/pages/account-settings/AccountSettingsConnections.vue'

const route = useRoute('settings-tab')

const activeTab = computed({
  get: () => route.params.tab,
  set: () => route.params.tab,
})

// tabs
const tabs = [
  {
    title: 'Account',
    icon: 'bx-user',
    tab: 'account',
  },
  {
    title: 'Security',
    icon: 'bx-lock-alt',
    tab: 'security',
  },
  {
    title: 'Billing & Plans',
    icon: 'bx-detail',
    tab: 'billing-plans',
  },
  {
    title: 'Notifications',
    icon: 'bx-bell',
    tab: 'notification',
  },
  {
    title: 'Connections',
    icon: 'bx-link',
    tab: 'connection',
  },
]

definePage({ meta: { navActiveLink: 'settings-tab' } })
</script>

<template>
  <div>
    <VTabs
      v-model="activeTab"
      class="v-tabs-pill"
    >
      <VTab
        v-for="item in tabs"
        :key="item.icon"
        :value="item.tab"
        :to="{ name: 'settings-tab', params: { tab: item.tab } }"
      >
        <VIcon
          size="20"
          start
          :icon="item.icon"
        />
        {{ item.title }}
      </VTab>
    </VTabs>

    <VWindow
      v-model="activeTab"
      class="mt-6 disable-tab-transition"
      :touch="false"
    >
      <!-- Account -->
      <VWindowItem value="account">
        <AccountSettingsAccount />
      </VWindowItem>

      <!-- Security -->
      <VWindowItem value="security">
        <AccountSettingsSecurity />
      </VWindowItem>

      <!-- Billing -->
      <VWindowItem value="billing-plans">
        <AccountSettingsBillingAndPlans />
      </VWindowItem>

      <!-- Notification -->
      <VWindowItem value="notification">
        <AccountSettingsNotification />
      </VWindowItem>

      <!-- Connections -->
      <VWindowItem value="connection">
        <AccountSettingsConnections />
      </VWindowItem>
    </VWindow>
  </div>
</template>
