<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import avatar3 from '@images/avatars/avatar-3.png'
import avatar4 from '@images/avatars/avatar-4.png'
import avatar5 from '@images/avatars/avatar-5.png'
import avatar6 from '@images/avatars/avatar-6.png'
import { useTheme } from 'vuetify';

const route = useRoute('administrator-admin-tab-id')
definePage({ meta: { navActiveLink: 'administrator-admin-tab-tab' } })

const vuetifyTheme = useTheme()

const getColorInTheme = computed(() => {
  return vuetifyTheme.name.value === 'dark' ? 'white' : '#525050';
});

const userID = computed(() => route.params.id);
const userName = computed(() => route.query.userName);
const userEmail = computed(() => route.query.userEmail);

const currentTab = ref('user')
const tabData = computed(() => {
  const data = {
    user: {
      tag: 'user',
      tableHeaders: [
        {
          title: 'NAME',
          key: 'name_user',
        },
        {
          title: 'EMAIL',
          key: 'email_user',
        },
        {
          title: 'Last Login',
          key: 'lastLogin_user',
        },
        {
          title: 'User Actions',
          key: 'userActions_user',
        },
      ],
      data: [
        {
          avatar: avatar1,
          userFullName: 'Albert Flores',
          email: 'af@kw.com',
          lastLogin: '8/10/2024',
        },
        {
          avatar: avatar1,
          userFullName: 'Albert Flores',
          email: 'af@kw.com',
          lastLogin: '8/10/2024',
        },
        {
          avatar: avatar1,
          userFullName: 'Albert Flores',
          email: 'af@kw.com',
          lastLogin: '8/10/2024',
        },
      ]
    },
    invoiceHistory: {
      tag: 'invoiceHistory',
      tableHeaders: [
        {
          title: 'DATE',
          key: 'date_ih',
        },
        {
          title: 'Invoice No.',
          key: 'invoiceNo_ih',
        },
        {
          title: 'AMOUNT',
          key: 'amt_ih',
        }
      ],
      data: [
        {
          date: '8/10/2024',
          time: '8:54am',
          invoiceNo: 'ZEX00254',
          amt: '$1234.55',
        },
        {
          date: '8/10/2024',
          time: '8:54am',
          invoiceNo: 'ZEX00254',
          amt: '$1234.55',
        },
        {
          date: '8/10/2024',
          time: '8:54am',
          invoiceNo: 'ZEX00254',
          amt: '$1234.55',
        },
      ]
    },
    notes: {
      tag: 'notes',
      tableHeaders: [
        {
          title: 'DATE',
          key: 'date_n',
        },
        {
          title: 'NOTE',
          key: 'note_n',
        },
        {
          title: 'AUTHOR',
          key: 'author_n',
        }
      ],
      data: [
        {
          date: '8/10/2024',
          time: '8:54am',
          note: 'Lorem ipsum lorem ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum',
          author: 'Nelson Sonny',
        },
        {
          date: '8/10/2024',
          time: '8:54am',
          note: 'Lorem ipsum lorem ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum',
          author: 'Nelson Sonny',
        },
        {
          date: '8/10/2024',
          time: '8:54am',
          note: 'Lorem ipsum lorem ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum ipsum Lorem ipsum lorem ipsum',
          author: 'Nelson Sonny',
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

const openActionLogVal = ref(false);
</script>

<template>
  <VCol>
    <div class="pa-4 mt-n4">
      <h2>
        <small>
          <router-link :to="{ name: 'administrator-admin-tab-tab', params: { tab: 'tab-1' } }" :style="`color: ${getColorInTheme}`">Companies</router-link>
          &nbsp; > &nbsp; </small> 
        [Company Name]
      </h2>
    </div>
    <VRow>
      <VCol cols="12" sm="12" md="6" lg="6">
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
      </VCol>
      <VCol cols="12" sm="12" md="6" lg="6">
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
      </VCol>
    </VRow>

    <VRow>
      <VCol cols="12">
        <VCard class="rounded-xl">

          <div class="pa-6">
            <VTabs
              v-model="currentTab"
              class="v-tabs-pill"
            >
              <VTab value="user">
                User
              </VTab>
              <VTab value="invoiceHistory" class="ml-2">
                Invoice History
              </VTab>
              <VTab value="notes" class="ml-2">
                Notes
              </VTab>
            </VTabs>
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
            item-value="name"
            hide-default-footer
            :search="search"
            show-select
          >
            <!-- Dynamic Template for 'date' or 'name' -->
            <!-- User Set -->
            <template #item.name_user="{ item }">
              <div class="d-flex gap-2 items-center align-center">
                <VAvatar
                  color="primary"
                  variant="tonal"
                >
                  <VImg :src="item.avatar" />
                </VAvatar>
                <div class="text-base text-high-emphasis">
                  {{ item.userFullName }}
                </div>
              </div>
            </template>
            <template #item.email_user="{ item }">
              <div>
                <h6 class="text-h6 text-no-wrap">
                  {{ item.email }}
                </h6>
              </div>
            </template>
            <template #item.lastLogin_user="{ item }">
              <div>
                <h6 class="text-h6 text-no-wrap">
                  {{ item.lastLogin }}
                </h6>
              </div>
            </template>
            <template #item.userActions_user="{ item }">
              <div @click="openActionLogVal = true">
                <h6 class="text-h6 text-no-wrap">
                  <div class="d-flex gap-4 cursor-pointer openActionLog">
                    Open Action Log
                    <span id="iconRedirect">
                      <VIcon icon="mdi-arrow-top-right" style="font-size:12px;margin-top:-4px;" />
                    </span>
                  </div>
                </h6>
              </div>
            </template>
            <!-- Invoice History Set -->
            <template #item.date_ih="{ item }">
              <div>
                <h6 class="text-h6 text-no-wrap">
                  {{ item.date }} <br/> <small>{{ item.time }}</small>
                </h6>
              </div>
            </template>
            <template #item.invoiceNo_ih="{ item }">
              <div>
                <h6 class="text-h6 text-no-wrap">
                  {{ item.invoiceNo }}
                </h6>
              </div>
            </template>
            <template #item.amt_ih="{ item }">
              <div class="">
                <h6 class="text-h6 text-no-wrap">
                  {{ item.amt }}
                </h6>
              </div>
            </template>
            <!-- Notes Set -->
            <template #item.date_n="{ item }">
              <div class="height100">
                {{ item.date }} <br/> <small>{{ item.time }}</small>
              </div>
            </template>
            <template #item.note_n="{ item }">
              <div>
                {{ item.note }}
              </div>
            </template>
            <template #item.author_n="{ item }">
              <div class="height100">
                {{ item.author }} <br/>
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
  <OpenActionLog v-model:isDialogVisible="openActionLogVal" />
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
