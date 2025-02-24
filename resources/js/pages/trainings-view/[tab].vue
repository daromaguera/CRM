<script setup>
import AddTrainingModuleDrawer from '@/views/drawers/AddTrainingModuleDrawer.vue'
import Tab1 from '@/views/pages/training-tabs/Tab1.vue'
import Tab2 from '@/views/pages/training-tabs/Tab2.vue'
import Tab3 from '@/views/pages/training-tabs/Tab3.vue'
import Tab4 from '@/views/pages/training-tabs/Tab4.vue'
import Tab5 from '@/views/pages/training-tabs/Tab5.vue'

const route = useRoute('administrator-training-tab-tab')

const activeTab = computed({
  get: () => route.params.tab,
  set: () => route.params.tab,
})

// tabs
const tabs = [
  {
    title: 'Filter Topic Tab 1',
    icon: 'bx-user',
    tab: 'tab-1',
  },
  {
    title: 'Filter Topic Tab 2',
    icon: 'bx-lock-alt',
    tab: 'tab-2',
  },
  {
    title: 'Filter Topic Tab 3',
    icon: 'bx-detail',
    tab: 'tab-3',
  },
  {
    title: 'Filter Topic Tab 4',
    icon: 'bx-bell',
    tab: 'tab-4',
  },
  {
    title: 'Filter Topic Tab 5',
    icon: 'bx-link',
    tab: 'tab-5',
  },
]

definePage({ meta: { navActiveLink: 'administrator-training-tab-tab' } })

const isAddModuleDrawerOpen = ref(false)
</script>

<template>
  <div>
    <div class="pa-4 mt-n4">
      <span style="position: absolute; right: 25px; cursor: pointer">
        <VBtn class="rounded-xl" @click="isAddModuleDrawerOpen = !isAddModuleDrawerOpen">
          Add Module
          <VIcon icon="mdi-plus" style="margin-left: 5px"></VIcon>
        </VBtn>
      </span>
      <h2>Training Library</h2>
    </div>

    <VCard class="rounded-xl">
      <VCardText class="pb-md-10">
        <VTabs v-model="activeTab" class="v-tabs-pill">
          <VTab
            v-for="item in tabs"
            :key="item.icon"
            :value="item.tab"
            :to="{ name: 'administrator-training-tab-tab', params: { tab: item.tab } }"
            class="d-flex gap-4 ma-1"
            style="font-size: 12px"
          >
            <VIcon start :icon="item.icon" />
            {{ item.title }}
          </VTab>
        </VTabs>

        <VWindow v-model="activeTab" class="mt-6 disable-tab-transition" :touch="false">
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
    <AddTrainingModuleDrawer v-model:isDrawerOpen="isAddModuleDrawerOpen" />
  </div>
</template>
