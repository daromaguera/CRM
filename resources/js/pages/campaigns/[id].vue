<script setup>
import avatar8 from '@images/avatars/avatar-8.png'
import xls from '@images/icons/file/xls.png'
import pdf from '@images/icons/project-icons/pdf.png'
import aviato from '@images/logos/aviato.png'
import bitbank from '@images/logos/bitbank.png'
import zipcar from '@images/logos/zipcar.png'
import TimelineRectangle1 from '@images/pages/TimelineRectangle1.png'
import TimelineRectangle2 from '@images/pages/TimelineRectangle2.png'
import TimelineRectangle3 from '@images/pages/TimelineRectangle3.png'
import TimelineRectangle4 from '@images/pages/TimelineRectangle4.png'
import { useTheme } from 'vuetify';

const route = useRoute('campaigns-id')
definePage({ meta: { navActiveLink: 'drip-campaigns' } })

const idDrip = computed(() => route.query.id);
const nameDrip = computed(() => route.query.name);
const descriptionDrip = computed(() => route.query.description);
const messageDrip = computed(() => route.query.message);
const timeDrip = computed(() => route.query.time);
const triggeredWords = computed(() => route.query.triggered_words);
const statusDrip = computed(() => route.query.status);
const unassignedContracts = computed(() => route.query.unassigned_contracts);
const assignedContracts = computed(() => route.query.assigned_contracts);
const closedContracts = computed(() => route.query.closed_contracts);
const channels = computed(() => route.query.channels);

const vuetifyTheme = useTheme()

const getColorInTheme = computed(() => {
  return vuetifyTheme.name.value === 'dark' ? 'white' : '#525050';
});

const albumImages = [
  TimelineRectangle1,
  TimelineRectangle2,
  TimelineRectangle3,
  TimelineRectangle4,
]

const earnings = [
  {
    avatar: zipcar,
    title: 'Zipcar',
    subtitle: 'Vuejs, React & HTML',
    amount: '$24,895.65',
    progress: 'primary',
  },
  {
    avatar: bitbank,
    title: 'Bitbank',
    subtitle: 'Sketch, Figma & XD',
    amount: '$8,6500.20',
    progress: 'info',
  },
  {
    avatar: aviato,
    title: 'Aviato',
    subtitle: 'HTML & Anguler',
    amount: '$1,2450.80',
    progress: 'secondary',
  },
]

const logisticData = ref([
  {
    icon: 'mdi-glassdoor',
    color: 'primary',
    title: 'Unassigned Contracts',
    value: unassignedContracts,
    isHover: false,
  },
  {
    icon: 'mdi-garage-open',
    color: 'warning',
    title: 'Assigned Contracts',
    value: assignedContracts,
    isHover: false,
  },
  {
    icon: 'mdi-book-open-page-variant',
    color: 'error',
    title: 'Closed Contracts',
    value: closedContracts,
    isHover: false,
  },
  {
    icon: 'mdi-telegram',
    color: 'info',
    title: 'Channels',
    value: channels,
    isHover: false,
  },
])

const timelineItems = ref([
  {
    id: 1,
    type: 'document',
    title: "You've uploaded doc pdf to the Themeselection project",
    description: 'The process of recording the key project details...',
    meta: "Step 1",
    file: { src: pdf, name: 'documentation.pdf' },
    images: [],
    color: 'blue',
  },
  {
    id: 2,
    type: 'album',
    title: 'Heather added 4 images to the Team album',
    description: 'In the Select Image for Project dialog box...',
    meta: "Step 2",
    file: "",
    images: [TimelineRectangle1, TimelineRectangle2, TimelineRectangle3, TimelineRectangle4],
    color: 'green',
  },
  {
    id: 3,
    type: 'album',
    title: 'Heather added 4 images to the Team album',
    description: 'In the Select Image for Project dialog box...',
    meta: "Step 3",
    file: "",
    images: [TimelineRectangle1, TimelineRectangle2, TimelineRectangle3, TimelineRectangle4],
    color: 'green',
  },
  {
    id: 4,
    type: 'document',
    title: "You've uploaded doc pdf to the Themeselection project",
    description: 'The process of recording the key project details...',
    meta: "Step 4",
    file: { src: pdf, name: 'documentation.pdf' },
    images: [],
    color: 'blue',
  },
])

const checkbox = ref(1)
const boxColor = ref('Warning')
</script>

<template>
  <div class="pa-4 mt-n4">
    <h2>
      <small>
        <router-link to="/drip-campaigns" :style="`color:${getColorInTheme}`">Campaigns</router-link>
        <router-link 
            :to="{ 
              name: 'campaigns-drip-edit-id', 
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
          >
          <VBtn size="small" class="rounded-xl float-right" @click="isEventHandlerSidebarActive = true"><VIcon icon="mdi-table-edit" class="pr-7" /> Customize or Edit Drip</VBtn>
        </router-link>
        &nbsp; > &nbsp; </small> 
      <span style="color:#469e8f"> {{ nameDrip.length > 20 ? nameDrip.slice(0, 20) + '...' : nameDrip }} </span>
    </h2>
  </div>
  <VRow>
    <VCol cols="12" lg="6" md="6" sm="12" xs="12" class="pa-6">
      {{ descriptionDrip }}
    </VCol>
    <VCol cols="12" lg="6" md="6" sm="12" xs="12">
      <VRow>
        <VCol
          v-for="(data, index) in logisticData"
          :key="index"
          cols="12"
          md="3"
          sm="6"
        >
          <div>
              <VCard
                class="logistics-card-statistics rounded-xl"
                :style="data.isHover ? `border-block-end-color: rgb(var(--v-theme-${data.color}))` : `border-block-end-color: rgba(var(--v-theme-${data.color}),0.38)`"
                @mouseenter="data.isHover = true"
                @mouseleave="data.isHover = false"
              >
                <VCardText>
                  <div class="mb-2" style="font-size:1em">
                    {{ data.title }}
                  </div>
                  <div class="d-flex align-center gap-x-4 mb-2">
                    <VAvatar
                      variant="tonal"
                      size="25"
                      :color="data.color"
                      rounded
                    >
                      <VIcon
                        :icon="data.icon"
                        size="15"
                      />
                    </VAvatar>
                    <h4 style="font-size:1em">
                      {{ data.value }}
                    </h4>
                  </div>
                </VCardText>
              </VCard>
          </div>
        </VCol>
      </VRow>
    </VCol>
  </VRow>
  <VDivider class="mt-6"  />
  <br>
  <div class="my-6 mt-6">


    <VTimeline
      align="start"
      line-inset="19"
      truncate-line="start"
      justify="center"
      :density="$vuetify.display.smAndDown ? 'compact' : 'default'"
    >
    
        <!-- SECTION Timeline Item: Document -->
        <VTimelineItem
          v-for="item in timelineItems"
          :key="item.id"
          fill-dot
          size="small"
        >
          <template #opposite>
            <span class="app-timeline-meta ma-2">
              {{ item.meta }}
            </span>
          </template>
          <template #icon>
            <div class="v-timeline-avatar-wrapper rounded-xl">
              <div class="avatar-container">
                <VAvatar size="48" variant="tonal" color="info" class="p-4" />
                <VCheckbox 
                  v-model="checkbox"
                  :true-value="1"
                  color="info"
                  class="checkbox-overlay non-disabled"
                  aria-invalid=""
                />
              </div>
            </div>
          </template>
          <!-- 👉 Header -->
          <VCard class="mb-8 mt-n2 ma-4">
            <VCardItem class="pb-4">
              <VCardTitle>{{ item.title }}</VCardTitle>
            </VCardItem>
            <VCardText>
              <!-- 👉 Content -->
              <p class="app-timeline-text mb-3">{{ item.description }}</p>
              <div v-if="item.file.name" class="d-inline-flex align-items-center timeline-chip">
                <img
                  :src="pdf"
                  height="20"
                  class="me-2"
                  alt="img"
                >
                <span class="app-timeline-text font-weight-medium">{{ item.file.name }}</span>
              </div>
              <div class="d-flex gap-4 flex-wrap">
              <template
                v-for="(img, i) in item.images"
                :key="i"
              >
                <VImg :src="img" />
              </template>
            </div>
            </VCardText>
          </VCard>
        </VTimelineItem>
        <!-- !SECTION -->


    </VTimeline>
  </div>
</template>

<style lang="scss">
.v-timeline-avatar-wrapper {
  background-color: rgb(var(--v-theme-surface));
}

.timeline-rating {
  .v-rating__item {
    .v-btn--icon {
      --v-btn-height: 22px;
    }
  }
}
</style>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.75rem;
}
.avatar-container {
  position: relative;
  display: inline-block;
}

.checkbox-overlay {
  position: absolute;
  top: 17px;
  right: 25px;
  transform: translate(30%, -30%); /* Adjust position */
  border-radius: 50%;
}
.non-disabled {
  pointer-events: none; /* Prevents interaction but keeps normal appearance */
  opacity: 1 !important; /* Ensures it doesn’t look disabled */
}
</style>
