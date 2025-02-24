<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import avatar3 from '@images/avatars/avatar-3.png'
import avatar4 from '@images/avatars/avatar-4.png'
import avatar5 from '@images/avatars/avatar-5.png'
import avatar6 from '@images/avatars/avatar-6.png'
import { useTheme } from 'vuetify';

const route = useRoute('crm-contact-details-id')
definePage({ meta: { navActiveLink: 'root' } })

const vuetifyTheme = useTheme()

const getColorInTheme = computed(() => {
  return vuetifyTheme.name.value === 'dark' ? 'white' : '#525050';
});

const contactID = computed(() => route.params.id);
const userName = computed(() => route.query.name);
const timeOnDoors = computed(() => route.query.timeOnDoors);
const doorsKnocked = computed(() => route.query.doorsKnocked);
const appointmentsSet = computed(() => route.query.appointmentsSet);
const contacts = computed(() => route.query.contacts);
const addr = computed(() => route.query.addr);
const phone = computed(() => route.query.phone);
const email = computed(() => route.query.email);
const years_in_home = computed(() => route.query.years_in_home);
const possible_equity = computed(() => route.query.possible_equity);
const rough_credit_score = computed(() => route.query.rough_credit_score);
const zillow_estimate = computed(() => route.query.zillow_estimate);

const userData = JSON.parse(localStorage.getItem('userData'));

const currentTab = ref('contracts')
const tabData = computed(() => {
  const data = {
    contracts: {
      tag: 'contracts',
      tableHeaders: [
        {
          title: 'TITLE',
          key: 'title',
        },
        {
          title: 'DATE SENT',
          key: 'dateSent',
          align: 'end',
        },
        {
          title: 'DATE SIGNED',
          key: 'dateSigned',
          align: 'end',
        },
      ],
      data: [
        {
          id: 1,
          title: "Contract Name",
          dateSent: '8/10/2024',
          dateSigned: '8/10/2024',
        },
        {
          id: 2,
          title: "Contract Name",
          dateSent: '8/10/2024',
          dateSigned: '8/10/2024',
        },
        {
          id: 3,
          title: "Contract Name",
          dateSent: '8/10/2024',
          dateSigned: '8/10/2024',
        },
        {
          id: 4,
          title: "Contract Name",
          dateSent: '8/10/2024',
          dateSigned: '8/10/2024',
        },
      ]
    },
    dripCampaign: {
      tag: 'dripCampaign',
      tableHeaders: [
        {
          title: 'DRIP NAME',
          key: 'drip_name',
        },
        {
          title: '# OF MESSAGES',
          key: 'no_messages',
          align: 'end',
        },
        {
          title: 'DATE INITIATED',
          key: 'date_initiated',
          align: 'end',
        },
        {
          title: 'NEXT MESSAGE DATE',
          key: 'next_message_date',
          align: 'end',
        }
      ],
      data: [
        {
          id: 1,
          drip_name: 'Text Reminders',
          no_messages: '6',
          date_initiated: '8/10/2024',
          next_message_date: '8/12/2024',
        },
        {
          id: 2,
          drip_name: 'Text Reminders',
          no_messages: '6',
          date_initiated: '8/10/2024',
          next_message_date: '8/12/2024',
        },
        {
          id: 3,
          drip_name: 'Text Reminders',
          no_messages: '6',
          date_initiated: '8/10/2024',
          next_message_date: '8/12/2024',
        },
        {
          id: 4,
          drip_name: 'Text Reminders',
          no_messages: '6',
          date_initiated: '8/10/2024',
          next_message_date: '8/12/2024',
        },
      ]
    },
    notes: {
      tag: 'notes',
      tableHeaders: [
        {
          title: 'TITLE',
          key: 'title_n',
        },
        {
          title: 'NOTE',
          key: 'note_n',
        },
        {
          title: 'DATE',
          key: 'date_n',
        }
      ],
      data: [
        {
          id: 1,
          title: 'Reminder',
          note: 'Lorem ipsum lorem ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum',
          date: '8/10/2024',
        },
        {
          id: 2,
          title: 'Reminder',
          note: 'Lorem ipsum lorem ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum',
          date: '8/10/2024',
        },
        {
          id: 3,
          title: 'Reminder',
          note: 'Lorem ipsum lorem ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum',
          date: '8/10/2024',
        },
        {
          id: 4,
          title: 'Reminder',
          note: 'Lorem ipsum lorem ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum',
          date: '8/10/2024',
        },
      ]
    }
  }
  
  return data[currentTab.value]
})

const search = ref('')

const options = ref({
  itemsPerPage: 5,
  page: 1,
})

const openActionEditContact = ref(false);
</script>

<template>
  <VCol>
    <div class="pa-4 mt-n4">
      <h2>
        <small>
          <router-link :to="{ name: 'root' }" :style="`color:${getColorInTheme}`">CRM</router-link>
          &nbsp; > &nbsp; 
        </small> 
        <small v-if="userData.user_role_id == 1">
          <router-link 
            :to="{ 
              name: 'crm-id', 
              params: { id: contactID },
              query: { 
                id: contactID,
                name: userName,
                timeOnDoors: timeOnDoors,
                doorsKnocked: doorsKnocked,
                contacts: contacts,
                appointmentsSet: appointmentsSet
              } 
            }"
            :style="`color:${getColorInTheme}`"
          >Status</router-link>
          &nbsp; > &nbsp; 
        </small> 
        <span style="color:#469e8f">Contact Information</span>
      </h2>
    </div>
    <VRow>
      <VCol cols="12" sm="12" md="6" lg="6">
        <VCard class="mb-6 rounded-xl">
          <VCardText class="d-flex flex-column gap-y-6">
            <span style="position:absolute;right:25px;top:35px;cursor: pointer;">
              <VBtn class="rounded-xl" @click="openActionEditContact = true"><div class="d-flex gap-2 align-center"><i class="bx-pencil"></i> Edit</div></VBtn>
            </span>
            <div class="d-flex align-center gap-x-3">
              <VAvatar
                size="40"
                :image="avatar1"
              />
              <div>
                <h5 class="text-h5">
                  {{ userName }}
                </h5>
                <!-- <div class="text-body-1">
                  {{ userEmail }}
                </div> -->
              </div>
            </div>

            <div class="d-flex gap-x-3 align-center">
              <VRow>
                <VCol cols="5">
                  <small>Email</small><br/>
                  {{ email }}
                </VCol>
                <VCol cols="7" class="text-right">
                  <small>Years in Home</small><br/>
                  {{ years_in_home }}
                </VCol>
              </VRow>
            </div>
            <div class="d-flex gap-x-3 align-center mt-n3">
              <VRow>
                <VCol cols="5">
                  <small>Phone</small><br/>
                  {{ phone }}
                </VCol>
                <VCol cols="7" class="text-right">
                  <small>Possible Equity</small><br/>
                  {{ possible_equity }}
                </VCol>
              </VRow>
            </div>
            <div class="d-flex gap-x-3 align-center mt-n3">
              <VRow>
                <VCol cols="5">
                  <small>[Misc. Info]</small><br/>
                  Info Info Ipsum
                </VCol>
                <VCol cols="7" class="text-right">
                  <small>Rough Credit Score</small><br/>
                  {{ rough_credit_score }}
                </VCol>
              </VRow>
            </div>
            <div class="d-flex gap-x-3 align-center mt-n3">
              <VRow>
                <VCol cols="5">
                  <small>Address</small><br/>
                  {{ addr }}
                </VCol>
              </VRow>
            </div>

          </VCardText>
        </VCard>
      </VCol>
      <!-- <VCol cols="12" sm="12" md="6" lg="6">
        <VCard class="mb-6 rounded-xl">
          <VCardText class="d-flex flex-column gap-y-6">
            <span style="position:absolute;right:25px;top:35px;cursor: pointer;">
              <VBtn class="rounded-xl">Impersonate</VBtn>
            </span>
            <div class="d-flex align-center gap-x-3">
              <VAvatar
                size="40"
                :image="avatar1"
              />
              <div>
                <h5 class="text-h5">
                  {{ userName }}
                </h5>
                <div class="text-body-1">
                  {{ userEmail }}
                </div>
              </div>
            </div>

            <div class="d-flex gap-x-3 align-center">
              <VRow>
                <VCol cols="5">
                  <small>Email</small><br/>
                  email.gasw@gmail.com
                </VCol>
                <VCol cols="7" class="text-right">
                  <small>Last Login</small><br/>
                  Sample Content 2
                </VCol>
              </VRow>
            </div>
            <div class="d-flex gap-x-3 align-center mt-n3">
              <VRow>
                <VCol cols="5">
                  <small>Phone</small><br/>
                  +23 903-30513-22
                </VCol>
                <VCol cols="7" class="text-right">
                  <small>Subscription Status</small><br/>
                  <VChip
                    label
                    size="small"
                    color="success"
                  >
                    Active
                  </VChip>
                </VCol>
              </VRow>
            </div>
            <div class="d-flex gap-x-3 align-center mt-n3">
              <VRow>
                <VCol cols="5">
                  <small>[Misc. Info]</small><br/>
                  Info Info Ipsum
                </VCol>
                <VCol cols="7" class="text-right">
                  <small>Subscription Renewal</small><br/>
                  Feb. 5, 2026
                </VCol>
              </VRow>
            </div>
            <div class="d-flex gap-x-3 align-center mt-n3">
              <VRow>
                <VCol cols="5">
                  <small>Billing Address</small><br/>
                  125 West Samson St.<br/>
                  Lehi, Utah 848485
                </VCol>
                <VCol cols="7" class="text-right">
                  <small>Subscription Start</small><br/>
                  Feb. 5, 2024
                </VCol>
              </VRow>
            </div>

          </VCardText>
        </VCard>
      </VCol> -->
    </VRow>

    <VRow>
      <VCol cols="12">
        <VCard class="rounded-xl">

          <div class="pa-6">
            <VTabs
              v-model="currentTab"
              class="v-tabs-pill"
            >
              <VTab value="contracts">
                Contracts
              </VTab>
              <VTab value="dripCampaign" class="ml-2">
                Drip Campaign
              </VTab>
              <VTab value="notes" class="ml-2">
                Notes
              </VTab>
            </VTabs>
          </div>

          <VDivider class="" />
          <div class="pa-2">
            <VRow class="d-flex align-center">
              <VCol cols="12" lg="2" md="2" sm="2" xs="2">
                <div class="d-flex gap-2 pl-10 justify-center align-center">
                  <span style="font-size:1.3em;">Status</span>
                  <VChip
                    label
                    size="small"
                    color="success"
                  >
                    Active
                  </VChip>
                </div>
              </VCol>
              <VCol cols="12" lg="10" md="10" sm="10" xs="10" style="padding:0px;height:10px;">
                <VProgressLinear
                  model-value="78"
                  height="12"
                  color="primary"
                  style="width:90%;"
                />
              </VCol>
            </VRow>
          </div>
          <VDivider class="" />

          <VCardItem class="pt-6">
            <div class="d-flex justify-space-between align-center flex-wrap gap-4">
              <div style="inline-size: 250px;">
                <AppTextField
                  v-model="search"
                  placeholder="Search Project"
                />
              </div>
              <div class="d-flex gap-2">
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
              </div>
            </div>
          </VCardItem>
          <VDivider />
          <!-- 👉 User Project List Table -->

          <!-- SECTION Datatable -->
          <VDataTable
            v-model:page="options.page"
            :headers="tabData.tableHeaders"
            :items-per-page="options.itemsPerPage"
            :items="tabData.data"
            item-value="id"
            hide-default-footer
            :search="search"
            show-select
          >
            <!-- Dynamic Template for 'date' or 'name' -->
            <!-- Contracts Set -->
            <template #item.title="{ item }">
              <div class="text-base text-high-emphasis">
                {{ item.title }}
              </div>
            </template>
            <template #item.dateSent="{ item }">
              <div>
                <h6 class="text-h6 text-no-wrap">
                  {{ item.dateSent }}
                </h6>
              </div>
            </template>
            <template #item.dateSigned="{ item }">
              <div>
                <h6 class="text-h6 text-no-wrap">
                  {{ item.dateSigned }}
                </h6>
              </div>
            </template>

            <!-- Drip Campaign Set -->
            <template #item.drip_name="{ item }">
              <div>
                <h6 class="text-h6 text-no-wrap">
                  {{ item.drip_name }}
                </h6>
              </div>
            </template>
            <template #item.no_messages="{ item }">
              <div>
                <h6 class="text-h6 text-no-wrap">
                  {{ item.no_messages }}
                </h6>
              </div>
            </template>
            <template #item.date_initiated="{ item }">
              <div class="">
                <h6 class="text-h6 text-no-wrap">
                  {{ item.date_initiated }}
                </h6>
              </div>
            </template>
            <template #item.next_message_date="{ item }">
              <div class="">
                <h6 class="text-h6 text-no-wrap">
                  {{ item.next_message_date }}
                </h6>
              </div>
            </template>

            <!-- Notes Set -->
            <template #item.title_n="{ item }">
              <div class="height100">
                {{ item.title }} <br/>
              </div>
            </template>
            <template #item.note_n="{ item }">
              <div>
                {{ item.note }}
              </div>
            </template>
            <template #item.date_n="{ item }">
              <div class="height100">
                {{ item.date }} <br/>
              </div>
            </template>

            <!-- Bottom Pagination -->
            <template #bottom>
              <TablePagination
                v-model:page="options.page"
                :items-per-page="options.itemsPerPage"
                :total-items="tabData.data.length"
              />
            </template>
          </VDataTable>

          <VDivider />

        </VCard>
      </VCol>

    </VRow>
  </VCol>
  <EditContact 
    v-model:isDialogVisible="openActionEditContact" 
    v-model:contactID="contactID" 
    v-model:userName="userName"
    v-model:addr="addr"
    v-model:phone="phone"
    v-model:email="email"
    v-model:years_in_home="years_in_home"
    v-model:possible_equity="possible_equity"
    v-model:rough_credit_score="rough_credit_score"
    v-model:zillow_estimate="zillow_estimate"
    v-model:userType="userData.user_role_id"
  />
</template>

<style scoped>
.height100{
  height:100%;
}
#iconRedirect{
  border:1px solid #aeaeae;position:absolute;right:25px;cursor: pointer;border-radius:5px;width:21px;height:21px;text-align: center;
}
.openActionLog:hover{
  color:rgb(25, 155, 160);
}
/* #iconRedirect:hover{
  border:2px solid rgb(25, 155, 160);
  background:rgb(25, 155, 160);
  color:white;
} */
#iconRedirect2{
  border:1px solid #aeaeae;cursor: pointer;border-radius:5px;padding:5px;
}
#iconRedirect2:hover{
  border:2px solid rgb(25, 155, 160);
  background:rgb(25, 155, 160);
  color:white;
}
.openActionLog:hover #iconRedirect{
  border:2px solid rgb(25, 155, 160);
  background:rgb(25, 155, 160);
  color:white;
}
</style>
