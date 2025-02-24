<script setup>
import { useTrainingLibrary } from '@/composables/training_library/training_library'
import { useAuthUserStore } from '@/stores/authUser'
import AddTrainingModuleDrawer from '@/views/drawers/AddTrainingModuleDrawer.vue'
import { onMounted, ref } from 'vue'

// Import tab components
import Tab from '@/views/pages/user-training-tab/Tab1.vue'

// Auth Store
const authUserStore = useAuthUserStore()

// Dynamic Tabs
const activeTab = ref(null) // Default to first tab when loaded
const editTrainingLibrary = ref({})
const isAddModuleDrawerOpen = ref(false)
const addItemFlag = ref(false)

// Fetch Training Library Data
const {
  current_page,
  per_page,
  last_page,
  total,
  trainingLibraries,
  listForTrainingLibrary,
  listForTrainingLibraryTopics,
  trainingLibraryTopics,
} = useTrainingLibrary()

onMounted(async () => {
  await listForTrainingLibraryTopics()
  if (trainingLibraryTopics.value.length > 0) {
    activeTab.value = trainingLibraryTopics.value[0]?.id // Default to first topic ID
  }
})

// Watch for Tab Clicks & Fetch Only If Not Already Fetched
watch(activeTab, async (newTab, oldTab) => {
  if (newTab && newTab !== oldTab) {
    await listForTrainingLibrary(current_page, newTab)
  }
})

// Helper Functions
const FetchData = (e) => {
  trainingLibraries.value = e
  addItemFlag.value = true
  editTrainingLibrary.value = {}
}

const openDrawer = (e) => {
  editTrainingLibrary.value = e
  if (editTrainingLibrary?.value?.id) {
    isAddModuleDrawerOpen.value = true
  }
}

const refreshData = (e) => {
  addItemFlag.value = e
}

// Map Topics to Components (Modify as Needed)
const tabComponents = {
  1: Tab,
}
</script>

<template>
  <div>
    <div class="pa-4 mt-n4">
      <span
        style="position: absolute; right: 25px; cursor: pointer"
        v-if="authUserStore.authUser?.['user_role_id'] == 1"
      >
        <VBtn class="rounded-xl" @click="isAddModuleDrawerOpen = !isAddModuleDrawerOpen">
          Add Module
          <VIcon icon="mdi-plus" style="margin-left: 5px"></VIcon>
        </VBtn>
      </span>
      <h2>Training Library</h2>
    </div>

    <VCard class="rounded-xl">
      <VCardText class="pb-md-10">
        <!-- Dynamic Tabs -->
        <VTabs v-model="activeTab" class="v-tabs-pill">
          <VTab
            v-for="topic in trainingLibraryTopics"
            :key="topic.id"
            :value="topic.id"
            class="d-flex gap-4 ma-1"
            style="font-size: 12px"
          >
            <VIcon start icon="bx-detail" />
            {{ topic.topic }}
          </VTab>
        </VTabs>

        <!-- Dynamic Tab Content -->
        <VWindow v-model="activeTab" class="mt-6 disable-tab-transition" :touch="false">
          <VWindowItem v-for="topic in trainingLibraryTopics" :key="topic.id" :value="topic.id">
            <component
              :is="tabComponents[topic.id] || Tab"
              v-if="trainingLibraries.length > 0"
              :tlibraries="trainingLibraries"
              :addItemFlag="addItemFlag"
              :activeTab="activeTab"
              @open-drawer="openDrawer"
              @refresh-data="refreshData"
              :current_page="current_page"
              :last_page="last_page"
              :per_page="per_page"
              :total="total"
              :editTrainingLibrary="editTrainingLibrary"
            />
          </VWindowItem>
        </VWindow>
      </VCardText>
    </VCard>

    <!-- Add Training Module Drawer -->
    <AddTrainingModuleDrawer
      v-model:isDrawerOpen="isAddModuleDrawerOpen"
      @fetch-data="FetchData"
      :editTrainingLibrary="editTrainingLibrary"
      :activeTab="activeTab"
    />
  </div>
</template>
