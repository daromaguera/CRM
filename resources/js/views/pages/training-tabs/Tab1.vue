<script setup>
import trainingPic from '@images/trainings/trainingPic.png'

const tableHeaders = [
  {
    title: 'TITLE',
    key: 'title',
  },
  {
    title: 'DESCRIPTION',
    key: 'desc',
  },
  {
    title: 'DATE CREATED',
    key: 'dateCreated',
  },
]

const search = ref('')

const options = ref({
  itemsPerPage: 5,
  page: 1,
})

const tableView = ref(false)
const toggleIconView = () => {
  tableView.value = !tableView.value;
}
const cardData = [
  {
    id: 1,
    img: trainingPic,
    title: 'Understanding & Overcoming Rejection',
    desc: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    dateCreated: '09/24/2024',
    script: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  },
  {
    id: 2,
    img: trainingPic,
    title: 'Understanding & Overcoming Rejection',
    desc: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    dateCreated: '09/24/2024',
    script: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  },
  {
    id: 3,
    img: trainingPic,
    title: 'Understanding & Overcoming Rejection',
    desc: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    dateCreated: '09/24/2024',
    script: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  },
  {
    id: 4,
    img: trainingPic,
    title: 'Understanding & Overcoming Rejection',
    desc: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    dateCreated: '09/24/2024',
    script: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  },
  {
    id: 5,
    img: trainingPic,
    title: 'Understanding & Overcoming Rejection',
    desc: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    dateCreated: '09/24/2024',
    script: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  },
  {
    id: 6,
    img: trainingPic,
    title: 'Understanding & Overcoming Rejection',
    desc: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    dateCreated: '09/24/2024',
    script: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  },
]
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
  <VRow>
    <!-- Search and Dropdown Table and Card Options/Features -->
    <VCol cols="12">
      <div class="d-flex justify-space-between align-center flex-wrap gap-4 pb-4">
        <div style="inline-size: 250px;">
          <AppTextField
            v-model="search"
            placeholder="Search Project"
          />
        </div>
        <div class="d-flex gap-4 justify-center align-center pl-10" style="overflow: auto;">
          <AppSelect
            :model-value="options.itemsPerPage"
            :items="[
              { value: 5, title: 'Edit' },
              { value: 10, title: 'Date' },
              { value: 25, title: 'Invoice No.' },
              { value: -1, title: 'Amount' },
            ]"
            style="inline-size: 10rem;"
            @update:model-value="options.itemsPerPage = parseInt($event, 10)"
          />
          <AppSelect
            :model-value="options.itemsPerPage"
            :items="[
              { value: 5, title: 'Sort by' },
              { value: 10, title: 'Date' },
              { value: 25, title: 'Invoice No.' },
              { value: -1, title: 'Amount' },
            ]"
            style="inline-size: 10rem;"
            @update:model-value="options.itemsPerPage = parseInt($event, 10)"
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
        v-model:page="options.page"
        :headers="tableHeaders"
        :items-per-page="options.itemsPerPage"
        :items="cardData"
        item-value="id"
        hide-default-footer
        :search="search"
        show-select
        class="mt-n4"
      >
        <!-- Dynamic Template for 'date' or 'name' -->
        <template #item.title="{ item }">
          <div style="height:100%;">
            <router-link 
              :to="{ 
                name: 'administrator-training-tab-id', 
                params: { id: item.id }, 
                query: { 
                  trainingImg: item.img,
                  trainingTitle: item.title,
                  trainingDesc: item.desc,
                  trainingDateCreated: item.dateCreated,
                  trainingScript: item.script
                } 
              }"
            >
              {{ item.title }}
            </router-link>
          </div>
        </template>
        
        <!-- Invoice No -->
        <template #item.desc="{ item }">
          <div>
            {{ item.desc }}
          </div>
        </template>

        <!-- Amount -->
        <template #item.dateCreated="{ item }">
          <div style="height:100%;">
            {{ item.dateCreated }}
          </div>
        </template>

        <!-- Bottom Pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="options.page"
            :items-per-page="options.itemsPerPage"
            :total-items="cardData.length"
          />
        </template>
      </VDataTable>
    </VCol>
    <!-- Card View Section -->
    <VCol v-else cols="12">
      <VRow>
        <VCol cols="12" sm="12" md="4" lg="4" v-for="(card, index) in cardData" :key="index">
          
            <VCard class="mainCard">
              <VCardText>
                <router-link 
                  :to="{ 
                    name: 'administrator-training-tab-id', 
                    params: { id: card.id }, 
                    query: { 
                      trainingImg: card.img,
                      trainingTitle: card.title,
                      trainingDesc: card.desc,
                      trainingDateCreated: card.dateCreated,
                      trainingScript: card.script
                    } 
                  }"
                >
                  <div class="d-flex justify-center align-start mb-4 bg-light-primary rounded-lg">
                    <VImg
                      :src=card.img
                      style="width:100%"
                      class="rounded-lg"
                    />
                  </div>
                </router-link>
                <div>
                  <h5 class="text-h5 mb-2">
                    {{ card.title }}
                  </h5>
                  <div class="" style="height:50px">
                    <div class="d-flex gap-1">
                      <div class="tabsTable buttonText">Tags</div>
                      <div class="tabsTable buttonText">Corporate</div>
                      <div class="tabsTable buttonText">Subject Matter</div>
                      <div class="tabsTable buttonText">Management</div>
                    </div>
                    <MoreBtn :menu-list="buttonList" class="buttonIcon buttonBackIcon" style="border:1px solid #aeaeae;" />
                  </div>
                  <div class="text-body-1">
                    {{ card.desc }}
                  </div>
                  <div class="d-flex justify-space-between my-4 flex-wrap gap-4">
                    <VBtn color="error" variant="tonal" size="small" style="border-radius:25px;">text</VBtn>
                    <div class="d-flex gap-2">
                      <VBtn color="primary" variant="tonal" size="small" style="border-radius:25px;">Edit</VBtn>
                      <VBtn color="primary" variant="tonal" size="small" style="border-radius:25px;">Preview</VBtn>
                    </div>
                  </div>
                </div>
              </VCardText>
            </VCard>

        </VCol>
      </VRow>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
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
