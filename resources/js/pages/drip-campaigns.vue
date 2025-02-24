<script setup>
import {
  formAddDripCamp,
  useDripCampaign,
} from '@/composables/auth/drip/useDripCampaign';
import { useDripCampaignStore } from '@/stores/dripCampaign';
import { toast } from 'vue3-toastify';
import { useTheme } from 'vuetify';

const dripCampaignStore = useDripCampaignStore()

const userData = JSON.parse(localStorage.getItem('userData'));

if(userData == null) {
  router.replace('/login')
}

const formData = ref(structuredClone(formAddDripCamp))
const vuetifyTheme = useTheme()

const getColorInTheme = computed(() => {
  return vuetifyTheme.name.value === 'dark' ? 'white' : '#525050';
});

const { fetchDripCampaignBaseInfo, submitNewDripCampaign, searchDripCampaign } = useDripCampaign()

var tableHeaders = ref([])
var tableData = ref([])

const openActionAddNewCampaign = ref(false);

const cpy_itemsPerPage = ref(0)
const options = ref({
  itemsPerPage: 10,
  page:1,
  total: 5,
})

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
        const { data, total } = await searchDripCampaign(
          search.value, 
          1, 
          options.value.itemsPerPage, 
          2
        );
        checkResultData(data, total);
      } else {
        spinner.value = false;
      }
    }, 2000);
  } else {
    assignValue(dripCampaignStore.drip_campaigns, dripCampaignStore.totalDC);
  }
};

const handleOnEnter = async () => {
  msgSearch.value = '';
  enterStatus.value = true;
  const { data, total } = await searchDripCampaign(
    search.value, 
    1, 
    options.value.itemsPerPage, 
    2
  );
  checkResultData(data, total);
  enterStatus.value = false;
};

const clearSearchText = () => {
  search.value = '';
  spinner.value = false
  assignValue(dripCampaignStore.drip_campaigns, dripCampaignStore.totalDC);
};

const checkResultData = (data, total) => {
  if (data?.length > 0) {
    msgSearch.value = `Showing result for "${search.value}"`;
    assignValue(data, total);
  } else {
    msgSearch.value = `No result for "${search.value}"`;
    assignValue(dripCampaignStore.drip_campaigns, dripCampaignStore.totalDC);
  }
  spinner.value = false;
};
// End for Search feature...

const assignValue = (data, total) => {
  options.value.total = total;
  if (data) {
    tableData.value = data.map(item => ({
      id: item.id,
      name: item.name,
      description: item.description,
      triggered_words: item.triggered_words,
      message: item.message,
      time: item.time,
      status: item.status,
      contracts: item.contracts,
      length: item.length,
      unassigned_contracts: item.unassigned_contracts,
      assigned_contracts: item.assigned_contracts,
      closed_contracts: item.closed_contracts,
      channels: item.channels
    }));
  }
}

const loadingSubmitNewDC = ref(false)
const formKey = ref(0);
const handleSubmit = async () => {
  if(userData.user_role_id === 2){
    loadingSubmitNewDC.value = true
    console.log("Form Data: ", formData.value)
    const { error, data, total } = await submitNewDripCampaign(formData.value, options.value.page, options.value.itemsPerPage, 2);
    if(!error){
      toast.success('New Drip Campaign was added successfully', toastOptions)
      openActionAddNewCampaign.value = false;
      loadingSubmitNewDC.value = false
      formData.value = structuredClone(formAddDripCamp);
      formKey.value++;
      assignValue(data, total);
    }else{
      toast.error('An Error occured. Please consult the administrator.', toastOptions)
    }
  }
}

const loadingDC = ref(false)
const initialize = async () => {
  if(dripCampaignStore.totalDC == 0){
    loadingDC.value = true
    const {data,total} = await fetchDripCampaignBaseInfo(options.value.page, options.value.itemsPerPage, 2);
    assignValue(data, total)
    loadingDC.value = false
  }else{
    assignValue(dripCampaignStore.drip_campaigns, dripCampaignStore.totalDC);
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
      title: 'CONTRACTS',
      key: 'contracts',
    },
    {
      title: 'LENGTH',
      key: 'length',
    },
    {
      title: 'TRIGGER WORDS',
      key: 'triggerWords',
    },
  ]
}

const handlePageChange = async(newPage) => {
  tableData.value = [];
  options.value.page = newPage;
  loadingDC.value = true
  const { data, total } = await fetchDripCampaignBaseInfo(newPage, options.value.itemsPerPage, userData.user_role_id);
  loadingDC.value = false
  assignValue(data, total);
}

const handlePageChangeItemsPerPage = async(newSetOfItems) => {
  if(cpy_itemsPerPage.value < newSetOfItems && dripCampaignStore.totalDC < newSetOfItems){
    tableData.value = [];
    if(search.value !== ""){
      loadingDC.value = true
      const {data,total} = await searchDripCampaign(search.value, 1, newSetOfItems, 2);
      loadingDC.value = false
      assignValue(data, total);
    }else{
      loadingDC.value = true
      const {data,total} = await fetchDripCampaignBaseInfo(options.value.page, newSetOfItems, 2);
      loadingDC.value = false
      assignValue(data, total);
    }
  }else{
    if(search.value !== ""){
      loadingDC.value = true
      const {data,total} = await searchDripCampaign(search.value, 1, newSetOfItems, 2);
      loadingDC.value = false
      assignValue(data, total);
    }
  }
  cpy_itemsPerPage.value = newSetOfItems
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
      <h2>Campaigns</h2>
    </div>
    <VCard class="pa-6 rounded-xl" :loading="loadingDC">
      <VRow>
        <VCol cols="12">
          <div class="d-flex justify-space-between align-center flex-wrap gap-4 pb-4">
            <div style="inline-size: 250px;">
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
            </div>
            <div class="d-flex gap-4 justify-center align-center pl-10" style="overflow: auto;">
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
                :disabled="loadingLogistics"
                @click="openActionAddNewCampaign = true"
              >
                New Campaign
              </VBtn>
            </div>
          </div>
          <VDivider />
          <div class="pa-1">
            <tt><small><i>Click name to view status</i></small></tt>
          </div>
          <VDivider />
        </VCol>
        <VCol cols="12">
          <VDataTable
            :page="options.page"
            :headers="tableHeaders"
            :items-per-page="options.itemsPerPage"
            :items="tableData"
            item-value="id"
            class="mt-n4"
            :style="`color:${getColorInTheme};`"
          >
            <!-- Dynamic Template for 'date' or 'name' -->
            <template #item.name="{ item }">
              <router-link 
                :to="{ 
                  name: 'campaigns-id', 
                  params: { id: item.id },
                  query: { 
                    id: item.id,
                    name: item.name,
                    description: item.description,
                    message: item.message,
                    time: item.time,
                    triggered_words: item.triggered_words,
                    status: item.status,
                    unassigned_contracts: item.unassigned_contracts,
                    assigned_contracts: item.assigned_contracts,
                    closed_contracts: item.closed_contracts,
                    channels: item.channels
                  } 
                }"
              >
                <div class="d-flex gap-2">
                  {{ item.name }} <span style="color:#aeaeae;"><i class='bx bx-right-top-arrow-circle'></i></span>
                </div>
              </router-link>
            </template>

            <template #item.status="{ item }">
              <div>
                <VChip
                  label
                  size="small"
                  :color="item.status === 'Active' ? 'success' : ''"
                >
                  {{ item.status }}
                </VChip>
              </div>
            </template>

            <template #item.contracts="{ item }">
              <div>
                {{ item.contracts }}
              </div>
            </template>

            <template #item.length="{ item }">
              <div>
                {{ item.length }}
              </div>
            </template>

            <template #item.triggerWords="{ item }">
              <div>
                {{ item.triggered_words }}
              </div>
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
  <AddNewCampaign v-model:isDialogVisible="openActionAddNewCampaign" :formKey="formKey" :onSubmit="handleSubmit" :form-data="formData" :loadingSubmitNewDC="loadingSubmitNewDC" />
</template>
