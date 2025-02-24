<script setup>
import { useTrainingLibrary } from '@/composables/training_library/training_library';
import moment from 'moment';
import { toast } from 'vue3-toastify';
import { useTheme } from 'vuetify';

const vuetifyTheme = useTheme();
const getColorInTheme = computed(() => (vuetifyTheme.name.value === 'dark' ? 'white' : '#525050'));

const search = ref('');
const options = ref({
  itemsPerPage: 10,
  page: 1,
});
const tableHeaders = [
  { title: 'TOPIC', key: 'topic' },
  { title: 'DISPLAY', key: 'is_display' },
  { title: 'CREATED DATE', key: 'created_at' },
  { title: 'ACTIONS', key: 'actions' },
];

const tableView = ref(false);
const toggleIconView = () => (tableView.value = !tableView.value);

// Fetch and manage training topics
const {
  trainingLibraryTopics,
  newTopic,
  listForTrainingLibraryTopicsAll,
  toggleDisplay,
  saveTrainingLibraryTopic,
  updateTrainingLibraryTopic,
  deleteTrainingLibraryTopic,
} = useTrainingLibrary();

onMounted(() => {
  listForTrainingLibraryTopicsAll();
});

// Manage modal state
const showAddTopicModal = ref(false);
const isEditing = ref(false);
const editingTopicId = ref(null);
const isDialogVisible = ref(false);
const topicId = ref('');


const openAddTopicModal = () => {
  newTopic.value = ''; // Reset input
  isEditing.value = false;
  showAddTopicModal.value = true;
};

const openEditTopicModal = (topic) => {
  newTopic.value = topic.topic;
  editingTopicId.value = topic.id;
  isEditing.value = true;
  showAddTopicModal.value = true;
};

const saveOrUpdateTopic = async () => {
  if (!newTopic.value.trim()) {
    toast.error('Topic title cannot be empty!', toastOptions);
    return;
  }

  if (isEditing.value) {
    await updateTrainingLibraryTopic(editingTopicId.value, newTopic.value);
  } else {
    await saveTrainingLibraryTopic();
  }

  showAddTopicModal.value = false;
};

const confirmDeleteTopic = async (id) => {
    isDialogVisible.value = true;
    topicId.value = id;
};
const closeDialog = async (e) => {
  isDialogVisible.value = e;
}

const deleteTopic = async (e) => {
    if(e) {
      await deleteTrainingLibraryTopic(topicId.value);
      isDialogVisible.value = false;
    }
}
</script>

<template>
  <div>
    <div class="pa-4 mt-n4">
      <VBtn size="small" class="rounded-xl float-right" @click="openAddTopicModal">
        <div class="d-flex gap-1">
          <VIcon icon="mdi-plus" /> Add Topic
        </div>
      </VBtn>
      <h2>Topics</h2>
    </div>

    <VCard class="pa-6 rounded-xl">
      <VRow>
        <VCol cols="12">
          <div class="d-flex justify-space-between align-center flex-wrap gap-4 pb-4">
            <div style="inline-size: 250px;">
              <AppTextField v-model="search" placeholder="Search Topic" />
            </div>
            <div class="d-flex gap-4 justify-center align-center pl-10" style="overflow: auto;">
              <AppSelect
                :model-value="options.itemsPerPage"
                :items="[{ value: 10, title: '10' }, { value: 25, title: '25' }, { value: 50, title: '50' }]"
                style="inline-size: 10rem;"
                @update:model-value="options.itemsPerPage = parseInt($event, 10)"
              />
              <Dialog 
               :isDialogVisible="isDialogVisible"
               :isLabel="'CONFIRM'"
               :isMessage="'Are you sure you want to delete?'"
               @update:modelValue="closeDialog"
               @confirm:modelValue="deleteTopic"
              />
              <span @click="toggleIconView">
                <VIcon v-if="tableView" icon="mdi-view-grid" class="cursor-pointer" color="primary" />
                <VIcon v-else icon="mdi-view-list" class="cursor-pointer" color="primary" />
              </span>
            </div>
          </div>
          <VDivider />
        </VCol>

        <VCol cols="12">
          <VDataTable
            v-model:page="options.page"
            :headers="tableHeaders"
            :items-per-page="options.itemsPerPage"
            :items="trainingLibraryTopics"
            item-value="id"
            hide-default-footer
            :search="search"
            show-select
            class="mt-n4"
            :style="`color:${getColorInTheme};`"
          >
            <template #item.topic="{ item }">
              <div>{{ item.topic }}</div>
            </template>

            <template #item.is_display="{ item }">
              <div @click="toggleDisplay(item.id)" class="display-button" style="cursor: pointer;">
                <VIcon :icon="item.is_display ? 'mdi-eye' : 'mdi-eye-off'" color="primary" />
              </div>
            </template>

            <template #item.created_at="{ item }">
              <div>{{ moment(item.created_at, 'YYYY-MM-DD HH:mm') }}</div>
            </template>

            <template #item.actions="{ item }">
              <VBtn icon size="small" color="primary" @click="openEditTopicModal(item)">
                <VIcon icon="mdi-pencil" />
              </VBtn>
              <VBtn icon size="small" color="error" @click="confirmDeleteTopic(item.id)">
                <VIcon icon="mdi-delete" />
              </VBtn>
            </template>

            <!-- Bottom Pagination -->
            <template #bottom>
              <TablePagination v-model:page="options.page" :items-per-page="options.itemsPerPage" :total-items="trainingLibraryTopics.length"/>
            </template>
          </VDataTable>
        </VCol>
      </VRow>
    </VCard>

    <!-- Add/Edit Topic Modal -->
    <VDialog v-model="showAddTopicModal" max-width="500px">
      <VForm ref="refVForm" @submit.prevent="saveOrUpdateTopic">
        <VCard>
          <VCardTitle>
            <span class="text-h6">{{ isEditing ? 'Edit Topic' : 'Add New Topic' }}</span>
          </VCardTitle>
          <VCardText>
            <VTextField v-model="newTopic" label="Topic Title" autofocus />
          </VCardText>
          <VCardActions>
            <VSpacer />
            <VBtn color="error" text @click="showAddTopicModal = false">Cancel</VBtn>
            <VBtn color="primary" type="submit">{{ isEditing ? 'Update' : 'Save' }}</VBtn>
          </VCardActions>
        </VCard>
      </VForm>
    </VDialog>
  </div>
</template>
