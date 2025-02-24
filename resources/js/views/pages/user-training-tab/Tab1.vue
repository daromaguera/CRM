<script setup>
import { useTrainingLibrary } from '@/composables/training_library/training_library';
import { useAuthUserStore } from '@/stores/authUser';
import moment from 'moment';
import { ref } from 'vue';

// Auth Store
const authUserStore = useAuthUserStore()

const tableHeaders = [
  {
    title: 'TITLE',
    key: 'title',
  },
  {
    title: 'DESCRIPTION',
    key: 'description',
  },
  {
    title: 'TAGS',
    key: 'tags',
  },
  {
    title: 'SCRIPT',
    key: 'script',
  },
  {
    title: 'DATE CREATED',
    key: 'created_at',
  },
]

const search = ref('')

// Define the tLibraries prop
const props = defineProps({
  tlibraries: {
    type: Array,
    required: true,
  },
  current_page: {
    type: Number,
    required: true,
  },
  per_page: {
    type: Number,
    required: true,
  },
  last_page: {
    type: Number,
    required: true,
  },
  total: {
    type: Number,
    required: true,
  },
  addItemFlag: {
    type: Boolean,
    required: true,
  },
  activeTab: {
    type: String,
    required: true,
  }
});

const options = ref({
  filter: 'all',
})

const filters = [
  { value: 'all', title: 'All Videos' },
  { value: 'is_favorite', title: 'Favorites' },
  { value: 'is_completed', title: 'Completed' },
]

const emit = defineEmits([
  'open-drawer',
  'refresh-data',
])

const tableView = ref(false)
const isLoading = ref(false);
const isDialogVisible = ref(false);
const deleteVideoId = ref('');

const toggleIconView = () => {
  tableView.value = !tableView.value;
}

const openDrawer = (e) => {
  emit('open-drawer', e);
}

const deleteVideoConfirm = (e) => {
  isDialogVisible.value = true;
  deleteVideoId.value = e;
}

const closeDialog = async (e) => {
  isDialogVisible.value = e;
}

const deleteVideoLibrary = async (e) => {
    if(e) {
      await deleteVideo(deleteVideoId.value, props.activeTab);
      isDialogVisible.value = false;
    }
}


// Handle dynamic page change
const updatePage = async (newPage) => {
  isLoading.value = true; // Start loading

  await nextTick(); // Ensure reactivity updates before fetching data

  current_page.value = newPage;

   emit('refresh-data', false);

  try {
    await options?._rawValue?.filter == ('is_favorite' || 'is_completed') ? getTrainingVideoWith(options?._rawValue?.filter, current_page, props.activeTab) : listForTrainingLibrary(newPage, props.activeTab);
  } finally {
    setTimeout(() => {
      isLoading.value = false; // Stop loading after 2 seconds
    }, 1000);
  }
};


const { listForTrainingLibrary, current_page, total, trainingLibraries, deleteVideo, toggleFavorite, getTrainingVideoWith } = useTrainingLibrary()


onMounted(() => {
  listForTrainingLibrary(current_page, props.activeTab)
})

</script>

<template>
  <VRow>
    <!-- Search and Dropdown Table and Card Options/Features -->
    <VCol cols="12">
      <div class="d-flex justify-space-between align-center flex-wrap gap-4 pb-4">
        <div style="inline-size: 250px;">
          <!-- <AppTextField
            v-model="search"
            placeholder="Search Project"
          /> -->
        </div>
        <div class="d-flex gap-4 justify-center align-center pl-10" style="overflow: auto;">
          <v-select
            v-model="options.filter"
            :items="filters"
            style="inline-size: 12rem;"
            @update:model-value="(value) => {
              options.filter = value;
              getTrainingVideoWith(value, current_page, props.activeTab);
            }"
          />
          <span @click="toggleIconView">
            <VIcon v-if="tableView" icon="mdi-view-grid" class="cursor-pointer" />
            <VIcon v-else icon="mdi-view-list" class="cursor-pointer" />
          </span>
        </div>
      </div>
      <VDivider />
      <div v-if="!tableView" class="pa-1">
        <tt><small><i>Click image to view training video</i></small></tt>
      </div>
      <VDivider v-if="!tableView" />
    </VCol>
    <!-- Table View Section -->
    <VCol v-if="tableView" cols="12">
      <VDataTable
        :page="current_page"
        :headers="tableHeaders"
        :items-per-page="per_page"
        :items="addItemFlag ? tlibraries : trainingLibraries"
        item-value="id"
        hide-default-footer
        :search="search"
        show-select
        class="mt-n4"
      >
        <template #item.title="{ item }">
          <div style="height:100%;">
            <router-link 
              :to="{ 
                name: 'trainings-view-id', 
                params: { id: item.id }, 
                query: { 
                  trainingTitle: item.title,
                  trainingDesc: item.description,
                  trainingDateCreated: item.created_at,
                  trainingScript: item.script,
                } 
              }"
            >
              {{ item.title }}
            </router-link>
          </div>
        </template>
        
        <template #item.description="{ item }">
          <div v-html="item.description">
          </div>
        </template>

        <template #item.tags="{ item }"> 
          <div class="" style="height:50px">
                      <div
                        v-for="(tag, tagIndex) in item.tags"
                        :key="tagIndex"
                        class="tabsTable buttonText"
                      >
                        {{ tag }}
                      </div>
                  </div>
        </template>

        <template #item.script="{ item }">
          <div v-html="item.script">
          </div>
        </template>

        <template #item.created_at="{ item }">
          <div style="height:100%;">
            {{ moment(item.created_at, "YYYY-MM-DD HH:mm") }}
          </div>
        </template>

        <!-- Bottom Pagination -->
        <template #bottom>
          <TablePagination
            :page="current_page"
            :items-per-page="per_page"
            :total-items="total"
            @update:page="updatePage"
          />
        </template>
      </VDataTable>
    </VCol>
    <!-- Card View Section -->
    <VCol v-else cols="12">
      <VRow>
        <VCol cols="12" sm="12" md="4" lg="4" v-for="(card, index) in addItemFlag ? tlibraries : trainingLibraries" :key="index">          
            <VCard class="mainCard">
              <VCardText>
                <router-link 
                  :to="{ 
                    name: 'trainings-view-id', 
                    params: { id: card.id }, 
                    query: { 
                      trainingVideo: card.tl_video_url,
                      trainingTitle: card.title,
                      trainingDesc: card.description,
                      trainingScript: card.script,
                      trainingTags: card.tags,
                      trainingDateCreated: card.created_at,
                      trainingLibId: card.id,
                      activeTab: activeTab,
                      current_page: current_page,
                    } 
                  }"
                >
                 <div class="d-flex justify-center align-start mb-4 bg-light-primary rounded-lg" v-if="card.thumbnail_url"
                 >
                    <VImg
                      :src="'https://' + card.thumbnail_url"
                      style="width:100%"
                      class="rounded-lg"
                    />
                  </div>
                  <div class="video-container position-relative" v-else>
                    <video
                      :src="'https://' + card.tl_video_url"
                      style="width: 100%;"
                      controls
                    >
                      Your browser does not support the video tag.
                    </video>
                  </div>
                </router-link>
                <div class="completed-badge" v-if="card?.training_library_users?.is_completed">Completed</div>
                <VIcon 
                    class="favorite-icon"
                    :class="{ 'text-yellow': card?.training_library_users?.is_favorite }"
                    :icon="card?.training_library_users?.is_favorite ? 'mdi-star' : 'mdi-star-outline'"    
                    @click.stop="toggleFavorite(card.id, current_page, props.activeTab)"
                
                  />
                <div>
                  <h5 class="text-h5 mb-2">
                    {{ card.title }}
                  </h5>
                  <div class="" style="height:50px">
                    <div class="d-flex gap-1">
                      <div
                        v-for="(tag, tagIndex) in card.tags"
                        :key="tagIndex"
                        class="tabsTable buttonText"
                      >
                        {{ tag }}
                      </div>
                    </div>
                  </div>
                  <div class="text-body-1" v-html="card.description"></div>
                  <div class="d-flex justify-space-between my-4 flex-wrap gap-4">
                    <div class="d-flex gap-2">
                      <VBtn color="primary" variant="tonal" size="small" style="border-radius:25px;" @click="openDrawer(card)" v-if="authUserStore.authUser?.['user_role_id'] == 1">Edit</VBtn>
                      <VBtn color="primary" variant="tonal" size="small" style="border-radius:25px;">
                        <router-link 
                          :to="{ 
                            name: 'trainings-view-id', 
                            params: { id: card.id }, 
                            query: { 
                              trainingVideo: card.tl_video_url,
                              trainingTitle: card.title,
                              trainingDesc: card.description,
                              trainingScript: card.script,
                              trainingTags: card.tags,
                              trainingDateCreated: card.created_at,
                              trainingLibId: card.id,
                              activeTab: activeTab,
                              current_page: current_page,
                            } 
                          }"
                        >Preview
                      </router-link>
                      </VBtn>
                      <VBtn color="error" variant="tonal" size="small" style="border-radius:25px;" v-if="authUserStore.authUser?.['user_role_id'] == 1" @click="deleteVideoConfirm(card.id)">Delete</VBtn>
                    </div>
                  </div>
                  <div class="text-body-1">
                      <small>Date Uploaded</small><br>
                      <small>{{ moment(card.created_at, "YYYY-MM-DD HH:mm") }}</small>
                    </div>
                </div>
              </VCardText>
            </VCard>
        </VCol>
      </VRow>
      <Dialog 
          :isDialogVisible="isDialogVisible"
          :isLabel="'CONFIRM'"
          :isMessage="'Are you sure you want to delete?'"
          @update:modelValue="closeDialog"
          @confirm:modelValue="deleteVideoLibrary"
        />
      <VProgressLinear
          indeterminate
          color="primary"
          style="width:100%;"
          v-if="isLoading"
        />
      <TablePagination
        :page="current_page"
        :items-per-page="per_page"
        :total-items="total"
        @update:page="updatePage"
      />

    </VCol>

  </VRow>
</template>

<style lang="scss" scoped>
.completed-badge {
  position: absolute;
  top: 30px;
  right: 30px;
  background: green;
  color: white;
  padding: 4px 8px;
  font-size: 12px;
  border-radius: 5px;
  font-weight: bold;
}

.video-container {
  position: relative;
  display: inline-block;
}

.favorite-icon {
  position: absolute;
  top: 30px;
  left: 30px;
  font-size: 24px;
  cursor: pointer;
  border-radius: 50%;
  padding: 5px;
}

.text-yellow {
  color: gold !important;
}

#iconRedirect{
  border:1px solid #aeaeae;position:absolute;right:25px;top:35px;cursor: pointer;border-radius:5px;width:21px;height:21px;text-align: center;
}
#iconRedirect:hover{
  border:2px solid rgb(25, 155, 160);
  background:rgb(25, 155, 160);
  color:white;
}
#iconRedirect2{
  border:1px solid #aeaeae;cursor: pointer;border-radius:5px;padding:5px;
}
#iconRedirect2:hover{
  border:2px solid rgb(25, 155, 160);
  background:rgb(25, 155, 160);
  color:white;
}
.tabsTable{
  background:#ececec;
  border-radius:25px;
  color:black;
  padding:4px;
  padding-left:7px;
  padding-right:7px;
  margin:2px;
  font-size:0.7em;
}

.mainCard {
  border: 3px solid transparent; /* Default transparent border */
  box-sizing: border-box;       /* Ensure border is included in dimensions */
  transition: border-color 0.3s ease; /* Smooth hover effect */
}
.mainCard:hover{
  border:3px solid rgb(22, 104, 228);
}
.buttonBackIcon{ display:none }
.buttonText{ display:block; }
@media (max-width: 460px) {
  .buttonText { display: none; }
  .buttonIcon { display: block; }
}
</style>
