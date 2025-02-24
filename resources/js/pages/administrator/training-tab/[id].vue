<script setup>
import { VRow } from 'vuetify/components';
import { useTheme } from 'vuetify';

const theme = useTheme();
const route = useRoute('administrator-training-tab-id')
definePage({ meta: { navActiveLink: 'administrator-training-tab-tab' } })

const userID = computed(() => route.params.id);
const trainingImg = computed(() => route.query.trainingImg);
const trainingTitle = computed(() => route.query.trainingTitle);
const trainingDesc = computed(() => route.query.trainingDesc);
const trainingDateCreated = computed(() => route.query.trainingDateCreated);
const trainingScript = computed(() => route.query.trainingScript);

const search = ref('')

const options = ref({
  itemsPerPage: 5,
  page: 1,
})
const buttonList = [
  {
    title: 'Tags',
    value: 'Tags',
  },
  {
    title: 'Corporate',
    value: 'Corporate',
    to: 'https://facebook.com',
  },
  {
    title: 'Subject Matter',
    value: 'Subject Matter',
  },
  {
    title: 'Management',
    value: 'Management',
  },
]
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
          name: 'administrator-training-tab-tab', 
          params: { tab: 'tab-1' },
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
          <img
            :src="trainingImg"
            style="width: 100%; height: auto; border-radius: 8px;"
            class="rounded-lg"
          />
        </div>
        
        <div class="vidTitle mt-4">
          <b>{{ trainingTitle }}</b>
        </div>
        
        <div class="pa-2" style="height:50px">
          <div class="d-flex gap-6">
            <div class="tabsTable buttonText">Tags</div>
            <div class="tabsTable buttonText">Corporate</div>
            <div class="tabsTable buttonText">Subject Matter</div>
            <div class="tabsTable buttonText">Management</div>
          </div>
          <MoreBtn :menu-list="buttonList" class="buttonIcon buttonBackIcon" style="border:1px solid #aeaeae;" />
        </div>

        <div class="vidDescription mt-4">
          {{ trainingDesc }}
        </div>

        <div class="mt-6">
          <span class="ffTl"><b>Script</b></span>
          <div :class="`videoScript mt-2 pa-2 rounded-lg ${theme.name.value === 'dark' ? 'darkBG' : 'lightBG'}`">
            <tt>{{ trainingScript }}</tt>
          </div>
        </div>

      </VCard>
      <VBtn variant="tonal" color="primary" class="pl-6 ma-4 mt-6 float-right" rounded><VIcon icon="mdi-check" /> Mark as Completed</VBtn>
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
