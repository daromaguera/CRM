<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import avatar3 from '@images/avatars/avatar-3.png'
import avatar4 from '@images/avatars/avatar-4.png'
import avatar5 from '@images/avatars/avatar-5.png'
import avatar6 from '@images/avatars/avatar-6.png'

const tableHeaders = [
  {
    title: 'NAME',
    key: 'name',
  },
  {
    title: 'EMAIL',
    key: 'email',
  },
  {
    title: 'Location',
    key: 'addr',
  },
  {
    title: 'Renewal Date',
    key: 'renewalDate',
  },
  {
    title: 'Last Login',
    key: 'addrSince',
  },
  {
    title: 'ACTIONS',
    key: 'actions',
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
    name: 'Keller Williams',
    email: 'info@kw.com',
    addr: 'Lehi, Utah',
    addrSince: 'Jan. 6, 2024',
    renewalDate: 'Feb. 5, 2024',
  },
  {
    id: 2,
    name: 'Lebron James',
    email: 'james.lebron@kw.com',
    addr: 'Texas, addr 123',
    addrSince: 'Mar. 6, 2024',
    renewalDate: 'Dec. 5, 2024',
  },
  {
    id: 3,
    name: 'Kevon Looney',
    email: 'kevon.looney@kw.com',
    addr: 'Huston, addr 12423',
    addrSince: 'Feb. 6, 2024',
    renewalDate: 'Nov. 5, 2024',
  },
  {
    id: 4,
    name: 'Kyle Lowry',
    email: 'kyle.lowry@kw.com',
    addr: 'Vegas, addr 424',
    addrSince: 'May. 6, 2024',
    renewalDate: 'Aug. 5, 2024',
  },
  {
    id: 5,
    name: 'Keller Williams',
    email: 'info@kw.com',
    addr: 'Lehi, Utah',
    addrSince: 'Jan. 6, 2024',
    renewalDate: 'Feb. 5, 2024',
  },
  {
    id: 6,
    name: 'Lebron James',
    email: 'james.lebron@kw.com',
    addr: 'Texas, addr 123',
    addrSince: 'Mar. 6, 2024',
    renewalDate: 'Dec. 5, 2024',
  },
  {
    id: 7,
    name: 'Kevon Looney',
    email: 'kevon.looney@kw.com',
    addr: 'Huston, addr 12423',
    addrSince: 'Feb. 6, 2024',
    renewalDate: 'Nov. 5, 2024',
  },
  {
    id: 8,
    name: 'Kyle Lowry',
    email: 'kyle.lowry@kw.com',
    addr: 'Vegas, addr 424',
    addrSince: 'May. 6, 2024',
    renewalDate: 'Aug. 5, 2024',
  },
  {
    id: 9,
    name: 'Keller Williams',
    email: 'info@kw.com',
    addr: 'Lehi, Utah',
    addrSince: 'Jan. 6, 2024',
    renewalDate: 'Feb. 5, 2024',
  },
  {
    id: 10,
    name: 'Lebron James',
    email: 'james.lebron@kw.com',
    addr: 'Texas, addr 123',
    addrSince: 'Mar. 6, 2024',
    renewalDate: 'Dec. 5, 2024',
  },
  {
    id: 11,
    name: 'Kevon Looney',
    email: 'kevon.looney@kw.com',
    addr: 'Huston, addr 12423',
    addrSince: 'Feb. 6, 2024',
    renewalDate: 'Nov. 5, 2024',
  },
  {
    id: 12,
    name: 'Kyle Lowry',
    email: 'kyle.lowry@kw.com',
    addr: 'Vegas, addr 424',
    addrSince: 'May. 6, 2024',
    renewalDate: 'Aug. 5, 2024',
  }
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
        class="text-no-wrap mt-n4"
      >
        <!-- Dynamic Template for 'date' or 'name' -->
        <template #item.name="{ item }">
          <div>
            {{ item.name }}
          </div>
        </template>
        
        <!-- Invoice No -->
        <template #item.email="{ item }">
          <div>
            {{ item.email }}
          </div>
        </template>

        <!-- Amount -->
        <template #item.addr="{ item }">
          {{ item.addr }}
        </template>

        <!-- Email -->
        <template #item.renewalDate="{ item }">
          {{ item.renewalDate }}
        </template>

        <!-- addrSince -->
        <template #item.addrSince="{ item }">
          {{ item.addrSince }}
        </template>

        <template #item.actions="{ item }">
          <router-link 
            :to="{ 
              name: 'administrator-admin-tab-id', 
              params: { id: item.id }, 
              query: { 
                userName: item.name,
                userEmail: item.email,
              } 
            }"
          >
            <span id="iconRedirect2">
              <VIcon icon="mdi-arrow-top-right" style="font-size:12px;margin-top:-4px;" />
            </span>
          </router-link>
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
          <VCard class="mb-6 mainCard">
            <VCardText class="d-flex flex-column gap-y-6">
              <router-link 
                :to="{ 
                  name: 'administrator-admin-tab-id', 
                  params: { id: card.id }, 
                  query: { 
                    userName: card.name,
                    userEmail: card.email,
                  } 
                }"
              >
                <span id="iconRedirect">
                  <VIcon icon="mdi-arrow-top-right" style="font-size:12px;margin-top:-4px;" />
                </span>
              </router-link>
              <div class="d-flex align-center gap-x-3">
                <VAvatar
                  size="40"
                  :image="avatar1"
                />
                <div>
                  <h5 class="text-h5">
                    {{ card.name }}
                  </h5>
                  <div class="text-body-1">
                    {{ card.email }}
                  </div>
                </div>
              </div>

              <div class="d-flex gap-x-3 align-center">
                <VRow>
                  <VCol cols="5">
                    <b>{{ card.addr }}</b><br/>
                    {{ card.addrSince }}
                  </VCol>
                  <VCol cols="7">
                    <b>Renewal Date</b><br/>
                    Since {{ card.renewalDate }}
                  </VCol>
                </VRow>
              </div>

              <div class="d-flex gap-x-3 align-center">
                <VRow>
                  <VCol cols="5">
                    [Misc. Info]<br/>
                    <b>Info Info Ipsum</b>
                  </VCol>
                  <VCol cols="7">
                    [Misc. Info Link]<br/>
                    <b><span>Company Contact Name</span></b>
                  </VCol>
                </VRow>
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
.mainCard {
  border: 3px solid transparent; /* Default transparent border */
  box-sizing: border-box;       /* Ensure border is included in dimensions */
  transition: border-color 0.3s ease; /* Smooth hover effect */
}
.mainCard:hover{
  border:3px solid rgb(22, 104, 228);
}
</style>
