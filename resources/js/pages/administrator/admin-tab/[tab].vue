<script setup>
import Tab1 from '@/views/pages/admin-tabs/Tab1.vue'
import Tab2 from '@/views/pages/admin-tabs/Tab2.vue'
import Tab3 from '@/views/pages/admin-tabs/Tab3.vue'
import Tab4 from '@/views/pages/admin-tabs/Tab4.vue'
import Tab5 from '@/views/pages/admin-tabs/Tab5.vue'

const route = useRoute('administrator-admin-tab-tab')

const activeTab = computed({
  get: () => route.params.tab,
  set: () => route.params.tab,
})

// tabs
const tabs = [
  {
    title: 'Tab 1',
    icon: 'bx-user',
    tab: 'tab-1',
  },
  {
    title: 'Tab 2',
    icon: 'bx-lock-alt',
    tab: 'tab-2',
  },
  {
    title: 'Tab 3',
    icon: 'bx-detail',
    tab: 'tab-3',
  },
  {
    title: 'Tab 4',
    icon: 'bx-bell',
    tab: 'tab-4',
  },
  {
    title: 'Tab 5',
    icon: 'bx-link',
    tab: 'tab-5',
  },
]

definePage({ meta: { navActiveLink: 'administrator-admin-tab-tab' } })
</script>

<template>
  <div>
    <div class="pa-4 mt-n4">
      <h2>Companies</h2>
    </div>

    <VCard class="rounded-xl">
      <VCardText class="pb-md-10">
        <VTabs
          v-model="activeTab"
          class="v-tabs-pill"
        >
          <VTab
            v-for="item in tabs"
            :key="item.icon"
            :value="item.tab"
            :to="{ name: 'administrator-admin-tab-tab', params: { tab: item.tab } }"
            class="d-flex gap-4 ma-1"
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
          <VWindowItem value="tab-1">
            <Tab1 />
          </VWindowItem>
          <VWindowItem value="tab-2">
            <Tab2 />
          </VWindowItem>
          <VWindowItem value="tab-3">
            <Tab3 />  
          </VWindowItem>
          <VWindowItem value="tab-4">
            <Tab4 />
          </VWindowItem>
          <VWindowItem value="tab-5">
            <Tab5 />
          </VWindowItem>

        </VWindow>
      </VCardText>
    </VCard>
  </div>
</template>
