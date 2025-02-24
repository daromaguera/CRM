<script setup>
import { useTheme } from 'vuetify';

const vuetifyTheme = useTheme()
definePage({ meta: { navActiveLink: 'root' } })

const getColorInTheme = computed(() => {
  return vuetifyTheme.name.value === 'dark' ? 'white' : '#525050';
});

const timeOnDoors = computed(() => route.query.timeOnDoors);
const doorsKnocked = computed(() => route.query.doorsKnocked);
const appointmentsSet = computed(() => route.query.appointmentsSet);
const contacts = computed(() => route.query.contacts);

const route = useRoute('crm-id')
const logisticData = ref([
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

const search = ref('')

const options = ref({
  itemsPerPage: 5,
  page: 1,
})
const tableHeaders = [
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
const tableData = [
  {
    id: 1,
    icon: 'mdi-clock',
    name: 'Calvin Hobby',
    status: ' < 45 Days',
    color: '#7d0f99',
    dfIcon: 'mdi-file',
    dealFlowStatus: 'Under Contract',
    addr: '1234 Dallin Dr.'
  },
  {
    id: 1,
    icon: 'mdi-alarm',
    name: 'Calvin Hobby',
    status: 'Appointment',
    color: '#1d9153',
    dfIcon: 'mdi-file',
    dealFlowStatus: 'Under Contract',
    addr: '1234 Dallin Dr.'
  },
  {
    id: 1,
    icon: 'mdi-alarm',
    name: 'Calvin Hobby',
    status: 'Appointment',
    color: '#1d9153',
    dfIcon: 'mdi-file',
    dealFlowStatus: 'Under Contract',
    addr: '1234 Dallin Dr.'
  },
  {
    id: 1,
    icon: 'mdi-alarm',
    name: 'Calvin Hobby',
    status: 'Appointment',
    color: '#1d9153',
    dfIcon: 'mdi-file',
    dealFlowStatus: 'Under Contract',
    addr: '1234 Dallin Dr.'
  },
]

const tableView = ref(false)
const toggleIconView = () => {
  tableView.value = !tableView.value;
}

const openActionAddContact = ref(false);
</script>

<template>
  <div>
    <div class="pa-4 mt-n4">
      <h2>
        <small>
          <router-link :to="{ name: 'root' }" :style="`color:${getColorInTheme}`">CRM</router-link>
          &nbsp; > &nbsp; </small> 
        <span style="color:#469e8f">Status</span>
      </h2>
    </div>
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
            class="logistics-card-statistics cursor-pointer rounded-xl"
            :style="data.isHover ? `border-block-end-color: rgb(var(--v-theme-${data.color}))` : `border-block-end-color: rgba(var(--v-theme-${data.color}),0.38)`"
            @mouseenter="data.isHover = true"
            @mouseleave="data.isHover = false"
          >
            <VCardText>
              <div class="d-flex align-center gap-x-4 mb-2">
                <VAvatar
                  variant="tonal"
                  size="40"
                  :color="data.color"
                  rounded
                >
                  <VIcon
                    :icon="data.icon"
                    size="24"
                  />
                </VAvatar>
                <h4 class="text-h4">
                  {{ data.value }}
                </h4>
              </div>
              <div class="text-body-1 mb-2">
                {{ data.title }}
              </div>
              <div class="d-flex gap-x-2 align-center">
                <h6 class="text-h6">
                  {{ (data.change > 0) ? '+' : '' }} {{ data.change }}%
                </h6>
                <div class="text-disabled">
                  than last week
                </div>
              </div>
            </VCardText>
          </VCard>
        </div>
      </VCol>
    </VRow>
    <VCard class="mt-6 pa-6 rounded-xl">
      <VRow>
        <VCol cols="12">
          <div style="height:40px">
            <VBtn size="small" class="rounded-xl float-right" @click="openActionAddContact = true"><VIcon icon="mdi-plus" /> Add Contact</VBtn>
          </div>
          <VDivider />
          <div class="d-flex justify-space-between align-center flex-wrap gap-4 pb-4 pt-4">
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
                <VIcon v-if="tableView" icon="mdi-view-grid" class="cursor-pointer" color="primary" />
                <VIcon v-else icon="mdi-view-list" class="cursor-pointer" color="primary" />
              </span>
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
            v-model:page="options.page"
            :headers="tableHeaders"
            :items-per-page="options.itemsPerPage"
            :items="tableData"
            item-value="id"
            hide-default-footer
            :search="search"
            show-select
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
              <span class="rounded-xl px-2 pb-1 elevation-1" :style="`background:${item.color};color:white;`">
                <small><VIcon :icon="item.icon" size="14" style="margin-top:-3px;" /> {{ item.status }}</small>
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
                v-model:page="options.page"
                :items-per-page="options.itemsPerPage"
                :total-items="tableData.length"
              />
            </template>
          </VDataTable>
        </VCol>
      </VRow>
    </VCard>
  </div>
  <AddContact v-model:isDialogVisible="openActionAddContact" />
</template>

<style lang="scss" scoped>
@use "@core-scss/base/mixins" as mixins;

.logistics-card-statistics {
  border-block-end-style: solid;
  border-block-end-width: 2px;

  &:hover {
    border-block-end-width: 3px;
    margin-block-end: -1px;

    @include mixins.elevation(8);

    transition: all 0.1s ease-out;
  }
}

.skin--bordered {
  .logistics-card-statistics {
    border-block-end-width: 2px;

    &:hover {
      border-block-end-width: 3px;
      margin-block-end: -2px;
      transition: all 0.1s ease-out;
    }
  }
}
</style>
