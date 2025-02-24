<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import { onMounted, ref, watchEffect } from 'vue'
import { useTheme } from 'vuetify'
import {
  formAddContact,
  useCRM,
} from '@/composables/auth/crm/useCRM'
import { useCRMStore } from '@/stores/crm'
import { toast } from 'vue3-toastify'
import { useRouter } from 'vue-router'

  // Router
  const router = useRouter()

const vuetifyTheme = useTheme()

const crmStore = useCRMStore()

const formData = ref(structuredClone(formAddContact))
const openActionAddContact = ref(false);

const { fetchCRMBaseInfo, addNewContact, searchContact } = useCRM(formData)

const getColorInTheme = computed(() => {
  return vuetifyTheme.name.value === 'dark' ? 'white' : '#525050'
})

const userData = JSON.parse(localStorage.getItem('userData'));

if(userData == null) {
  router.replace('/login')
}

// Logistic Data or Initial Data
const unAssignedContracts = ref(0);
const assignedContracts = ref(0);
const closeContracts = ref(0);
const channels = ref(0);
const logisticData = ref([
  {
    icon: 'mdi-paper-cut-vertical',
    color: 'warning',
    title: 'Unassigned Contracts',
    value: unAssignedContracts,
    isHover: false,
  },
  {
    icon: 'mdi-paperclip',
    color: 'success',
    title: 'Assigned Contracts',
    value: assignedContracts,
    isHover: false,
  },
  {
    icon: 'mdi-format-quote-close',
    color: 'secondary',
    title: 'Closed Contracts',
    value: closeContracts,
    isHover: false,
  },
  {
    icon: 'mdi-download-network',
    color: 'primary',
    title: 'Channels',
    value: channels,
    isHover: false,
  },
])

const timeOnDoors = ref(0);
const doorsKnocked = ref(0);
const appointmentsSet = ref(0);
const contacts = ref(0);
const logisticData2 = ref([
  {
    icon: 'mdi-glassdoor',
    color: 'primary',
    title: 'Time in Doors',
    value: timeOnDoors, // Convert to integer with fallback to 0
    change: 18.2,
    isHover: false,
  },
  {
    icon: 'mdi-garage-open',
    color: 'warning',
    title: 'Doors Knocked',
    value: doorsKnocked,
    change: -8.7,
    isHover: false,
  },
  {
    icon: 'mdi-book-open-page-variant',
    color: 'error',
    title: 'Appointments Set',
    value: appointmentsSet,
    change: 4.3,
    isHover: false,
  },
  {
    icon: 'mdi-telegram',
    color: 'info',
    title: 'Contacts Sent',
    value: contacts,
    change: -2.5,
    isHover: false,
  },
]);

// End Logistic Data or Initial Data

// Table Info and Data
var tableHeaders = ref([])
var tableData = ref([])

var tableHeaders_adm = ref([])
var tableData_adm = ref([])
// End Table Info and Data

const options = ref({
  itemsPerPage: 10,
  page:1,
  total: 0,
})

const cpy_itemsPerPage = ref(0)
const options_adm = ref({
  itemsPerPage: 10,
  page:1,
  total: 5,
})

const handlePageChange = async(newPage) => {
  tableData.value = [];
  options.value.page = newPage;
  const { data, total } = await fetchCRMBaseInfo(newPage, options.value.itemsPerPage, userData.user_role_id);
  assignValue(data, total);
}
// insert
const handlePageChangeItemsPerPage = async(newSetOfItems) => {
  if(cpy_itemsPerPage.value < newSetOfItems && crmStore.crm.length < newSetOfItems){
    tableData.value = [];
    if(search.value !== ""){
      const {data,total,logistics} = await searchContact(search.value, 1, newSetOfItems, 2);
      assignValue(data, total, logistics);
    }else{
      const {data,total,logistics} = await fetchCRMBaseInfo(options.value.page, newSetOfItems, 2);
      assignValue(data, total, logistics);
    }
  }else{
    if(search.value !== ""){
      const {data,total,logistics} = await searchContact(search.value, 1, newSetOfItems, 2);
      assignValue(data, total, logistics);
    }
  }
  cpy_itemsPerPage.value = newSetOfItems
}

const assignValue = (data, total, logistics) => {
  options.value.total = total;
  if (data) {
    tableData.value = data.map(item => ({
      id: item.id,
      icon: 'mdi-clock',
      name: item.fullname,
      status: item.status,
      dfIcon: 'mdi-file',
      diff_in_days: item.diff_in_days,
      dealFlowStatus: item.deal_flow_status,
      addr: item.address,
      phone: item.phone,
      email: item.email,
      years_in_home: item.years_in_home,
      possible_equity: item.possible_equity,
      rough_credit_score: item.rough_credit_score,
      zillow_estimate: item.zillow_estimate
    }));
  }
  if(logistics){
    unAssignedContracts.value = logistics[0].Unassigned_Contracts;
    assignedContracts.value = logistics[0].Assigned_Contracts;
    closeContracts.value = logistics[0].Closed_Contracts;
    channels.value = logistics[0].Channels;
    timeOnDoors.value = logistics[0].Time_In_Doors;
    doorsKnocked.value = logistics[0].Doors_Knocked;
    appointmentsSet.value = logistics[0].Appointments_Set;
    contacts.value = logistics[0].Contacts_Sent;
  }
}

const loadingSubmitContact = ref(false)
const handleSubmit = async () => {
  loadingSubmitContact.value = true
  const {data,total,logistics} = await addNewContact(formData.value, options.value.page, options.value.itemsPerPage, 2);
  if(data){
    toast.success('New Contact was added successfully', toastOptions)
    openActionAddContact.value = false;
    assignValue(data, total, logistics);
    formData.value = structuredClone(formAddContact);
  }else{
    toast.error('An Error occured. Please consult the administrator.', toastOptions)
  }
  loadingSubmitContact.value = false
}

// for Search feature...
const search = ref('');
const spinner = ref(false);
const enterStatus = ref(false);
const msgSearch = ref('');

const handleSearch = async () => {
  msgSearch.value = '';
  if (search.value !== '') {
    spinner.value = true;
    setTimeout(async () => {
      if (!enterStatus.value) {
        const { data, total, logistics } = await searchContact(
          search.value, 
          1, 
          options.value.itemsPerPage, 
          2
        );
        checkResultData(data, total, logistics);
      } else {
        spinner.value = false;
      }
    }, 2000);
  } else {
    assignValue(crmStore.crm, crmStore.totalCRM, crmStore.logisticsCRM);
  }
};

const handleOnEnter = async () => {
  msgSearch.value = '';
  enterStatus.value = true;
  const { data, total, logistics } = await searchContact(
    search.value, 
    1, 
    options.value.itemsPerPage, 
    2
  );
  checkResultData(data, total, logistics);
  enterStatus.value = false;
};

const clearSearchText = () => {
  search.value = '';
  spinner.value = false
  assignValue(crmStore.crm, crmStore.totalCRM, crmStore.logisticsCRM);
};

const checkResultData = (data, total, logistics) => {
  if (data?.length > 0) {
    msgSearch.value = `Showing result for "${search.value}"`;
    assignValue(data, total, logistics);
  } else {
    msgSearch.value = `No result for "${search.value}"`;
    assignValue(crmStore.crm, crmStore.totalCRM, crmStore.logisticsCRM);
  }
  spinner.value = false;
};
// End for Search feature...

const loadingLogistics = ref(false)
const initialize = async () => {
  if(userData.user_role_id === 1){  // Role - Administrator
    tableHeaders_adm.value = [
      {
        title: 'NAME',
        key: 'name',
      },
      {
        title: 'TIME ON DOORS',
        key: 'timeOnDoors',
        align: 'end',
      },
      {
        title: 'DOORS KNOCKED',
        key: 'doorsKnocked',
        align: 'end',
      },
      {
        title: 'CONTACTS',
        key: 'contacts',
        align: 'end',
      },
      {
        title: 'APPOINTMENTS SET',
        key: 'appointmentsSet',
        align: 'end',
      },
    ]
    tableData_adm.value = [
      {
        id: 1,
        avatar: avatar1,
        name: 'Albert Flores',
        timeOnDoors: '123',
        doorsKnocked: '123',
        contacts: '123',
        appointmentsSet: '123',
      },
      {
        id: 1,
        avatar: avatar1,
        name: 'Albert Flores',
        timeOnDoors: '123',
        doorsKnocked: '123',
        contacts: '123',
        appointmentsSet: '123',
      },
      {
        id: 1,
        avatar: avatar1,
        name: 'Albert Flores',
        timeOnDoors: '123',
        doorsKnocked: '123',
        contacts: '123',
        appointmentsSet: '123',
      },
      {
        id: 1,
        avatar: avatar1,
        name: 'Albert Flores',
        timeOnDoors: '123',
        doorsKnocked: '123',
        contacts: '123',
        appointmentsSet: '123',
      },
      {
        id: 1,
        avatar: avatar1,
        name: 'Albert Flores',
        timeOnDoors: '123',
        doorsKnocked: '123',
        contacts: '123',
        appointmentsSet: '123',
      },
    ]
  }else if(userData.user_role_id === 2){  // Role - RealTor
    if(crmStore.totalCRM === 0){
      loadingLogistics.value = true
      const {data,total,logistics} = await fetchCRMBaseInfo(options.value.page, options.value.itemsPerPage, 2);
      loadingLogistics.value = false
      assignValue(data, total, logistics);
    }else{
      assignValue( crmStore.crm, crmStore.totalCRM, crmStore.logisticsCRM );
    }
    tableHeaders.value = [
      {
        title: 'NAME',
        key: 'name',
      },
      {
        title: 'STATUS',
        key: 'status',
      },
      {
        title: 'DEAL FLOW STATUS',
        key: 'dealFlowStatus',
      },
      {
        title: 'ADDRESS',
        key: 'addr',
      },
    ]
  }else if(userData.user_role_id === 3){  // Role - Home owner

  }else if(userData.user_role_id === 4){  // Role - Company

  }
}

// Automatically load users on mount
onMounted(async () => {
  initialize();
  cpy_itemsPerPage.value = options.value.itemsPerPage;
})

</script>

<template>
  <div>
    <div class="pa-4 mt-n4">
      <h2>Good Morning, {{ userData.firstname }}</h2>
    </div>

    <LogisticStatisticCard :logisticData="logisticData" :additionalLogisticData="logisticData2" :userRoleId="userData.user_role_id" :loadingLogistics="loadingLogistics" />

    <VCard class="mt-6 pa-6 rounded-xl" :loading="crmStore.loadingWhileFetch">
      <VRow>
        <VCol cols="12" :class="{ 'blurred-icon': loadingLogistics }">
          <div class="d-flex justify-space-between align-center flex-wrap gap-4 pb-4 mt-4">
            <!-- <div class="d-flex">
              <div style="inline-size: 250px">
                <AppTextField v-model="search" clearable @click:clear="clearSearchText" @update:modelValue="handleSearch" @keydown.enter="handleOnEnter" placeholder="Search Contacts" :disabled="loadingLogistics" />
              </div>
              <VIcon v-if="spinner" class="custom-spin" icon="mdi-loading" spin></VIcon>
              <span v-if="msgSearch !== '' && search !== ''" class="pa-2 ml-2"><i>{{ msgSearch }}</i></span>
            </div> -->
            <SearchOnTable 
              v-model:search="search"
              :loading="spinner"
              :disabledField="loadingLogistics"
              :msgSearch="msgSearch"
              placeholder="Search Contacts"
              @onSearch="handleSearch"
              @onEnter="handleOnEnter"
              @onClear="clearSearchText"
            />
            <div class="d-flex gap-4 justify-center align-center pl-10" style="overflow: auto">
              <AppSelect
                v-model="options.itemsPerPage"
                :items="[5, 10, 20, 25, 50]"
                @update:modelValue="handlePageChangeItemsPerPage"
                :disabled="loadingLogistics"
              />
              <VBtn
                v-if="userData.user_role_id === 2"
                color="primary"
                prepend-icon="bx-plus"
                @click="openActionAddContact = true"
                :disabled="loadingLogistics"
              >
                Add Contact
              </VBtn>
            </div>
          </div>
          <VDivider />
          <div class="pa-1">
            <tt><small><i>Click name to view status</i></small></tt>
          </div>
          <VDivider />
        </VCol>
        <VCol v-if="userData.user_role_id === 1" cols="12">
          <VDataTable
            :page="options_adm.page"
            :headers="tableHeaders_adm"
            :items-per-page="options_adm.itemsPerPage"
            :items="tableData_adm"
            item-value="id"
            class="mt-n4"
            :style="`color:${getColorInTheme};text-align:right`"
          >
            <!-- Dynamic Template for 'date' or 'name' -->
            <template #item.name="{ item }">
              <router-link
                :to="{
                  name: 'crm-id',
                  params: { id: item.id },
                  query: {
                    id: item.id,
                    name: item.name,
                    timeOnDoors: item.timeOnDoors,
                    doorsKnocked: item.doorsKnocked,
                    contacts: item.contacts,
                    appointmentsSet: item.appointmentsSet,
                  },
                }"
              >
                <div class="d-flex gap-2">
                  <VAvatar size="30" :image="item.avatar" />
                  <div class="d-flex gap-4">
                    {{ item.name }}
                    <span style="color: #aeaeae"><i class="bx bx-right-top-arrow-circle"></i></span>
                  </div>
                </div>
              </router-link>
            </template>

            <!-- Bottom Pagination -->
            <template #bottom>
              <TablePagination
                :page="options_adm.page"
                :items-per-page="options_adm.itemsPerPage"
                :total-items="options_adm.total"
              />
            </template>
          </VDataTable>
        </VCol>
        <VCol v-if="userData.user_role_id === 2" cols="12">
          <VDataTable
            :page="options.page"
            :headers="tableHeaders"
            :items-per-page="options.itemsPerPage"
            :items="tableData"
            item-value="id"
            class="mt-n4"
            :style="`color:${getColorInTheme}`"
          >
            <!-- Dynamic Template for 'date' or 'name' -->
            <template #item.name="{ item }">
              <router-link 
                :to="{ 
                  name: 'crm-contact-details-id', 
                  params: { id: item.id },
                  query: { 
                    id: item.id,
                    name: item.name,
                    timeOnDoors: timeOnDoors,
                    doorsKnocked: doorsKnocked,
                    appointmentsSet: appointmentsSet,
                    contacts: contacts,
                    addr: item.addr,
                    phone: item.phone,
                    email: item.email,
                    years_in_home: item.years_in_home,
                    possible_equity: item.possible_equity,
                    rough_credit_score: item.rough_credit_score,
                    zillow_estimate: item.zillow_estimate
                  } 
                }"
              >
                <div class="d-flex gap-4">
                  {{ item.name }} <span style="color:#aeaeae;"><i class='bx bx-right-top-arrow-circle'></i></span>
                </div>
              </router-link>
            </template>
            
            <!-- Invoice No -->
            <template #item.status="{ item }">
              <span v-if="item.status == 1" class="rounded-xl px-2 pb-1 elevation-1" :style="`background:#318243;color:white;`">
                <small><VIcon :icon="item.icon" size="14" style="margin-top:-3px;" /> Appointment</small>
              </span>
              <span v-else-if="item.status == 2" class="rounded-xl px-2 pb-1 elevation-1" :style="`background:#9c4728;color:white;`">
                <small><VIcon :icon="item.icon" size="14" style="margin-top:-3px;" /> in {{ item.diff_in_days }} Day<span v-if="item.diff_in_days > 1">/s</span></small>
              </span>
              <span v-else class="rounded-xl px-2 pb-1 elevation-1" style="color:#706f6f">
                <small>No Appointment</small>
              </span>
            </template>

            <!-- Amount -->
            <template #item.dealFlowStatus="{ item }">
              <span class="rounded-xl px-2 pb-1 elevation-1" :style="`background:#ab220f;color:white;`">
                <small><VIcon :icon="item.dfIcon" size="14" style="margin-top:-3px;" /> {{ item.dealFlowStatus }}</small>
              </span>
            </template>

            <!-- Bottom Pagination -->
            <template #bottom>
              <TablePagination
                :page="options.page"
                :items-per-page="options.itemsPerPage"
                :total-items="options.total"
                @update:page="handlePageChange"
              />
            </template>
          </VDataTable>
        </VCol>
      </VRow>
    </VCard>
  </div>
  <AddContact v-model:isDialogVisible="openActionAddContact" :form-data="formData" :onSubmit="handleSubmit" :loadingSubmitContact="loadingSubmitContact" />
</template>

<style lang="scss" scoped>
.custom-spin {
  animation: spin 1s linear infinite;
  margin:8px;
}
.custom-spin2 {
  animation: spin 1s linear infinite;
}
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
.blurred-icon {
  filter: blur(3px);
  opacity: 0.5;
  color:grey;
}
</style>
