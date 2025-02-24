<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'
import { VForm } from 'vuetify/components/VForm'

import avatar8 from '@images/avatars/avatar-8.png'
import xls from '@images/icons/file/xls.png'
import pdf from '@images/icons/project-icons/pdf.png'
import TimelineRectangle1 from '@images/pages/TimelineRectangle1.png'
import TimelineRectangle2 from '@images/pages/TimelineRectangle2.png'
import TimelineRectangle3 from '@images/pages/TimelineRectangle3.png'
import TimelineRectangle4 from '@images/pages/TimelineRectangle4.png'
import {
  useDripCampaign,
} from '@/composables/auth/drip/useDripCampaign';
import { useRouter } from 'vue-router'

import { useTheme } from 'vuetify';
import { toast } from 'vue3-toastify';

const userData = JSON.parse(localStorage.getItem('userData'));

if(userData == null) {
  router.replace('/login')
}

const router = useRouter()
const { submitNewEdittedDripCampaign } = useDripCampaign()

const route = useRoute('campaigns-drip-edit-id')
definePage({ meta: { navActiveLink: 'drip-campaigns' } })

const idDrip = computed(() => route.query.id);
const nameDrip = computed(() => route.query.name);
const descriptionDrip = ref(route.query.description || "");
const messageDrip = ref(route.query.message || "");
const timeDrip = ref(route.query.time || "");
const triggeredWords = ref(route.query.triggered_words || "");
const statusDrip = computed(() => route.query.status);
const unassignedContracts = computed(() => route.query.unassigned_contracts);
const assignedContracts = computed(() => route.query.assigned_contracts);
const closedContracts = computed(() => route.query.closed_contracts);
const channels = computed(() => route.query.channels);

const nameDrip_cpy = computed(() => route.query.name);
const descriptionDrip_cpy = ref(route.query.description || "");
const messageDrip_cpy = ref(route.query.message || "");
const timeDrip_cpy = ref(route.query.time || "");
const triggeredWords_cpy = ref(route.query.triggered_words || "");

const vuetifyTheme = useTheme()

const getColorInTheme = computed(() => {
  return vuetifyTheme.name.value === 'dark' ? 'white' : '#525050';
});

const timelineItems = ref([
  {
    pos: 1,
    id: 1,
    type: 'document',
    title: "You've uploaded doc pdf to the Themeselection project",
    description: 'The process of recording the key project details...',
    meta: "2 month's ago",
    file: { src: pdf, name: 'documentation.pdf' },
    color: 'blue',
    icon: 'mdi-checkbox-marked',
    addElem: false,
  },
  {
    pos: 2,
    id: 2,
    type: 'album',
    title: 'Heather added 4 images to the Team album',
    description: 'In the Select Image for Project dialog box...',
    meta: "24 day's ago",
    images: [TimelineRectangle1, TimelineRectangle2, TimelineRectangle3, TimelineRectangle4],
    color: 'green',
    icon: 'mdi-checkbox-marked',
    addElem: false,
  },
  {
    pos: 3,
    id: 3,
    type: 'review',
    title: 'Loretta wrote a review on Themeselection',
    meta: "6 day's ago",
    user: { name: 'Loretta Moore', role: 'CTO of Airbnb', avatar: avatar8 },
    rating: 5,
    reviewText: 'I love how they constantly work on to make the template better...',
    color: 'purple',
    icon: 'mdi-checkbox-marked',
    addElem: false,
  },
  {
    pos: 4,
    id: 4,
    type: 'report',
    title: 'Julia Stiles shared an earnings report',
    meta: "2 day's ago",
    amount: '$24,895',
    comparison: '10% increase compared to last year',
    color: 'red',
    icon: 'mdi-checkbox-marked',
    addElem: false,
  },
  {
    pos: 5,
    id: 0,
    type: '',
    title: '',
    meta: "",
    file: "",
    progress: 0,
    color: '',
    icon: 'mdi-plus',
    addElem: true,
  },
])

const selectedTime = ref(new Date());

const addItem = ref(false)

const addItemNr = () => {
  addItem.value = !addItem.value;
}

const loadingSubmit = ref(false)
const validateSubmission = async () => {
  if(nameDrip.value === '' || descriptionDrip.value === '' || triggeredWords.value === '' || messageDrip.value === '' || timeDrip.value === ''){
    toast.error('Please fill required fields with asterisk (*)', toastOptions)
    return
  }else{
    if(nameDrip.value == nameDrip_cpy.value && descriptionDrip.value == descriptionDrip_cpy.value && triggeredWords.value == triggeredWords_cpy.value && messageDrip.value == messageDrip_cpy.value && timeDrip.value == timeDrip_cpy.value){
      toast.error('No changes have been made!', toastOptions)
    }else{
      window.scrollTo({
        top: 0,
        behavior: 'smooth' // Smooth scrolling effect (optional)
      });
      loadingSubmit.value = true
      const formAddDripCamp = {
        id: idDrip.value,
        dripName: nameDrip.value,
        description: descriptionDrip.value,
        triggerWords: triggeredWords.value,
        firstMessage: messageDrip.value,
        time: timeDrip.value,
      }
      var { error, updated_data } = await submitNewEdittedDripCampaign(formAddDripCamp, 1, 10, userData.user_role_id)
      if(!error){
        toast.success('Drip campaign has been successfully updated!', toastOptions)
        setTimeout(() => {
          router.push('/campaigns/drip-edit/'+updated_data.id+'?id='+updated_data.id+'&name='+updated_data.name+'&description='+updated_data.description+'&message='+updated_data.message+'&time='+updated_data.time+'&triggered_words='+updated_data.triggered_words+'&status='+statusDrip.value+'&unassigned_contracts='+unassignedContracts.value+'&assigned_contracts='+assignedContracts.value+'&closed_contracts='+closedContracts.value+'&channels='+channels.value+'')
        }, 1000);
      }else{
        toast.error('ERROR: Try again later or consult the system admninistrator.', toastOptions)
      }
      loadingSubmit.value = false
    }
  }
}
const refForm = ref()
</script>

<template>
  <VRow>
    <VCol cols="12" lg="6" md="6" sm="12" xs="12">
      <div class="pa-4 mt-n4">
        <h2>
          <small>
            <router-link to="/drip-campaigns" :style="`color:${getColorInTheme}`">Campaigns</router-link>
            &nbsp; > &nbsp; 
          </small> 
          <small>
            <router-link 
              :to="{ 
                name: 'campaigns-id', 
                params: { id: idDrip },
                query: { 
                  id: idDrip,
                  name: nameDrip,
                  description: descriptionDrip,
                  message: messageDrip,
                  time: timeDrip,
                  triggered_words: triggeredWords,
                  status: statusDrip,
                  unassigned_contracts: unassignedContracts,
                  assigned_contracts: assignedContracts,
                  closed_contracts: closedContracts,
                  channels: channels
                } 
              }"
              :style="`color:${getColorInTheme}`"
            > {{ nameDrip.length > 10 ? nameDrip.slice(0, 10) + '...' : nameDrip }} </router-link>
            &nbsp; > &nbsp; 
          </small> 
          <span style="color:#469e8f">Edit Drip</span>
        </h2>
      </div>
      <div class="pa-4 mt-n4">
        <div class="d-flex gap-3 align-center">
          Status
          <small>
            <VChip
              label
              size="small"
              :color="statusDrip === 'Active' ? 'success' : 'error'"
            >
              {{ statusDrip }}
            </VChip>
          </small>
        </div>
      </div>

      <VForm ref="refForm" @submit.prevent="validateSubmission">

        <VCard class="pa-6">
          <div v-if="loadingSubmit">
            <i>Saving changes, please wait... <VIcon class="custom-spin" icon="mdi-loading" spin></VIcon></i>
          </div>

          <div v-if="!loadingSubmit">
            <div class="py-2">
              <AppTextField
                v-model="nameDrip"
                label="Name of Your Drip"
                placeholder="Placeholder Text"
              />
            </div>
            <div class="py-2">
              <AppTextarea
                v-model="descriptionDrip"
                label="Description"
                placeholder="Placeholder Text"
              />
            </div>

            <div class="py-2">
              <AppTextarea
                v-model="triggeredWords"
                label="Trigger Words"
                placeholder="Placeholder Text"
              />
            </div>
            <h2>Start of Campaign</h2>
            <div class="py-2">
              <TiptapEditor
                v-model="messageDrip"
                class="border rounded basic-editor"
              />
            </div>
            <div class="py-2">
              <AppDateTimePicker
                v-model="timeDrip"
                label="Time picker"
                placeholder="Select time"
                :config="{ enableTime: true, noCalendar: true, dateFormat: 'h:i K', altInput: true, altFormat: 'h:i K', time_24hr: false }"
              />
            </div>
          </div>
        </VCard>
        <div v-if="!loadingSubmit" class="d-flex gap-2 justify-end pa-4">
          <VBtn variant="outlined" color="secondary">Cancel</VBtn> 
          <VBtn type="submit" color="primary">Save Changes</VBtn>
        </div>

      </VForm>
      
    </VCol>
    <VCol cols="12" lg="6" md="6" sm="12" xs="12">
      <div class="timeline-container my-6">
        <div class="pa-4 mt-n4">
          <h3>Drip Flow</h3>
          <div><small>Drag to Reorder</small></div>
        </div>
        <!-- Draggable VueTimeline -->
        <draggable
          v-model="timelineItems"
          item-key="id"
          group="timeline"
          animation="200"
          style="text-align:left;"
        >
          <template #item="{ element }">
            <v-timeline align="start" side="end" style="text-align:left !important;justify-content: left !important;" :disabled="element.addElem">
              <v-timeline-item :dot-color="element.color" :disabled="element.addElem" class="cursor-pointer">
                <!-- Custom Icon Slot -->
                <template #icon>
                  <v-icon size="24" :icon="element.icon" style="color:#47806a;" />
                </template>
                <VCard v-if="!element.addElem" class="pa-4">
                  <!-- Title and Meta -->
                  <div class="d-flex justify-space-between align-center mb-2">
                    <h3 class="title">{{ element.title }}</h3>
                  </div>

                  <!-- Description -->
                  <p v-if="element.description">{{ element.description }}</p>

                  <!-- File Section -->
                  <div v-if="element.file" class="file-chip d-flex align-center mt-2">
                    <img :src="element.file.src" alt="file" height="20" class="me-2" />
                    <span>{{ element.file.name }}</span>
                  </div>

                  <!-- Image Album -->
                  <div v-if="element.images" class="image-album d-flex gap-2 mt-2">
                    <VRow>
                      <VCol cols="12" lg="3" md="3" sm="12" xs="12" 
                      v-for="(img, i) in element.images"
                      :key="i">
                        <img
                        :src="img"
                        alt="album image"
                        style="width:100%"
                      />
                      </VCol>
                    </VRow>
                    
                  </div>

                  <!-- User Review -->
                  <div v-if="element.user" class="user-review mt-2">
                    <div class="d-flex align-center">
                      <img :src="element.user.avatar" alt="avatar" class="avatar me-2" />
                      <div>
                        <h5>{{ element.user.name }}</h5>
                        <p class="role">{{ element.user.role }}</p>
                      </div>
                    </div>
                    <div class="review-text mt-2">{{ element.reviewText }}</div>
                  </div>

                  <!-- Progress Bar -->
                  <div v-if="element.progress" class="progress-bar mt-2">
                    <div class="progress-label">{{ element.progress }}%</div>
                    <v-progress-linear
                      :value="element.progress"
                      height="8"
                      color="primary"
                    ></v-progress-linear>
                  </div>
                </VCard>
                <VCard v-if="element.addElem" class="pa-4" style="width:20em;">
                  <VBtn v-if="!addItem" @click="addItemNr" size="small" color="primary" style="width:100%">Add Item</VBtn>
                  <div v-if="addItem">
                    <div class="py-2">
                      <AppTextField
                        label="Title"
                        placeholder="Placeholder Text"
                      />
                    </div>
                    <div class="py-2">
                      <AppTextarea
                        label="Description"
                        placeholder="Placeholder Text"
                      />
                    </div>
                    <div class="d-flex gap-2 justify-end pa-4">
                      <VBtn size="small" variant="outlined" color="error" class="rounded-xl" @click="addItemNr">Cancel</VBtn> <VBtn size="small" class="rounded-xl">Save</VBtn>
                    </div>
                  </div>
                </VCard>
              </v-timeline-item>
            </v-timeline>
          </template>
        </draggable>
      </div>
    </VCol>
  </VRow>
      
</template>

<style scoped>
.timeline-container {
  padding: 1rem;
  text-align: left !important;
  margin: 0;
  padding: 0;
}

.title {
  font-weight: bold;
  font-size: 1.1rem;
}

.meta {
  font-size: 0.9rem;
  color: #888;
}

.file-chip,
.user-review {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
}

.image-album img {
  width: 80px;
  height: 80px;
  border-radius: 8px;
  object-fit: cover;
}

.progress-label {
  font-size: 0.8rem;
  color: #555;
  margin-bottom: 0.3rem;
}

.custom-spin {
  animation: spin 1s linear infinite;
  margin:8px;
}
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
