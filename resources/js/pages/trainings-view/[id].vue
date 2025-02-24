<script setup>
import { useTrainingLibrary } from '@/composables/training_library/training_library';
import { useTheme } from 'vuetify';
import { VRow } from 'vuetify/components';

const theme = useTheme();
const route = useRoute('trainings-view-id')
definePage({ meta: { navActiveLink: 'trainings' } })

const userID = computed(() => route.params.id);
const trainingVideo = computed(() => route.query.trainingVideo);
const trainingTitle = computed(() => route.query.trainingTitle);
const trainingDesc = computed(() => route.query.trainingDesc);
const trainingDateCreated = computed(() => route.query.trainingDateCreated);
const trainingScript = computed(() => route.query.trainingScript);
const trainingTags = computed(() => route.query.trainingTags);
const trainingLibId = computed(() => route.query.trainingLibId);
const activeTab = computed(() => route.query.activeTab);
const current_page = computed(() => route.query.current_page);
// const completed_status = computed(() => route.query.completed_status);


const options = ref({
  itemsPerPage: 5,
  page: 1,
})

const { markAsCompleted } = useTrainingLibrary()


</script>

<template>
  <VRow class="d-flex justify-center">
    <VCol cols="12" lg="8" md="8" sm="12" xs="12" class="d-flex justify-space-between">
      <div class="d-flex gap-4">
        <VBtn variant="tonal" color="primary" class="pr-6" rounded>
          <span class="buttonIcon"><VIcon icon="mdi-chevron-left" /></span>
          <span class="buttonText">Previous</span>
        </VBtn>
        <VBtn variant="tonal" color="primary" class="pl-6" rounded>
          <span class="buttonText">Next</span>
          <span class="buttonIcon"><VIcon icon="mdi-chevron-right" /></span>
        </VBtn>
      </div>
      <router-link 
        :to="{ 
          name: 'trainings', 
          query: { tab: 'tab-1' },
        }"
      >
      <VBtn variant="tonal" color="primary" rounded>
        <span class="buttonText">Back to Training Library</span>
        <span class="buttonIcon buttonBackIcon"><VIcon icon="mdi-backburger" /></span>
      </VBtn>
    </router-link>
    </VCol>
    <VCol cols="12" lg="8" md="8" sm="12" xs="12" class="">
      <VCard class="pa-5">
        <div>
          <video
            :src="'https://' + trainingVideo"
            style="width: 100%;"
            controls

          >
            Your browser does not support the video tag.
          </video>
        </div>
        
        <div class="vidTitle mt-4">
          <b>{{ trainingTitle }}</b>
        </div>
        
        <div class="pa-2" style="height:50px">
          <div class="d-flex gap-6">
            <div
              v-for="(tag, tagIndex) in trainingTags"
              :key="tagIndex"
              class="tabsTable buttonText"
            >
              {{ tag }}
            </div>
          </div>
        </div>

        <div class="vidDescription mt-4" v-html="trainingDesc"></div>

        <div class="mt-6">
          <span class="ffTl"><b>Script</b></span>
          <div :class="`videoScript mt-2 pa-2 rounded-lg ${theme.name.value === 'dark' ? 'darkBG' : 'lightBG'}`">
            <tt v-html="trainingScript"></tt>
          </div>
        </div>

      </VCard>
      <VBtn variant="tonal" color="primary" class="pl-6 ma-4 mt-6 float-right" rounded @click.stop="markAsCompleted(trainingLibId, current_page, activeTab)"><VIcon icon="mdi-check" /> Mark as Completed</VBtn>
    </VCol>
  </VRow>
</template>

<style scoped>
.darkBG{
  background: #403f3f;
}
.lightBG{
  background: #ececec;
}
.buttonBackIcon{ display:none }
.buttonText{ display:block; }
@media (max-width: 600px) {
  .buttonText { display: none; }
  .buttonIcon { display: block; }
}
.vidTitle{
  font-size:2em;
}
.tabsTable{
  background:#ececec;
  border-radius:25px;
  color:black;
  padding:4px;
  padding-left:15px;
  padding-right:15px;
  margin:2px;
}
.vidDescription{
  font-size:1em;
}
.videoScript{
  font-size:0.9em;
}
.ffTl{
  font-size:1.2em;
}
</style>
