<script setup>
import { useInvoiceData } from '@/composables/randomizer/useInvoiceData'
import { useTeamData } from '@/composables/randomizer/useTeamData'
import { useSpecificUserStore } from '@/stores/specificUser'
import BillingPanel from '@/views/apps/user/BillingPanel.vue'
import UserBioPanel from '@/views/apps/user/UserBioPanel.vue'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const userStore = useSpecificUserStore()
const userId = route.params.id

const { teamData, fetchTeamData } = useTeamData()
const { invoiceData, generateRandomInvoiceData } = useInvoiceData()

onMounted(() => {
  userStore.fetchUser(userId)
  fetchTeamData()
  generateRandomInvoiceData(30) // 30 random invoices
})

const currentTab = ref('invoice')
const tabData = computed(() => {
  const data = {
    invoice: {
      tag: 'invoice',
      tableHeaders: [
        { title: 'DATE', key: 'date' },
        { title: 'INVOICE NO.', key: 'invoiceNo' },
        { title: 'Amount', key: 'amt' },
      ],
      data: invoiceData.value,
    },
    team: teamData.value,
  }

  return data[currentTab.value]
})

const search = ref('')

const options = ref({
  itemsPerPage: 5,
  page: 1,
})

const billingDetails = computed(() => ({
  companyEmail: userStore.user.email,
  phone: userStore.user.phone,
  misc: 'Info Info Ipsum',
  billingAddress: `${userStore.user.city_municipality}, ${userStore.user.state_province}, ${userStore.user.country}`,
  subscriptionStatus: 'Active',
  renewsOn: 'Feb. 5, 2026',
  customerSince: 'Feb. 5, 2024',
}))
</script>

<template>
  <!-- <VRow v-if="userData"></VRow> -->
  <VRow>
    <VCol cols="12" md="6" lg="6">
      <UserBioPanel :user-data="userStore.user" />
    </VCol>
    <VCol cols="12" md="6" lg="6">
      <BillingPanel :billing-details="billingDetails" />
    </VCol>

    <VCol cols="12">
      <VRow>
        <VCol cols="12">
          <VCard class="rounded-xl">
            <div class="pa-6">
              <VTabs v-model="currentTab" class="v-tabs-pill">
                <VTab value="invoice"> Invoice History </VTab>
                <VTab value="team" class="ml-2"> Team Management </VTab>
              </VTabs>
            </div>

            <VDivider class="" />

            <VCardItem class="pt-6">
              <div class="d-flex justify-space-between align-center flex-wrap gap-4">
                <div style="inline-size: 250px">
                  <AppTextField v-model="search" placeholder="Search Project" />
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
                    style="inline-size: 10rem"
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
                    style="inline-size: 10rem"
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
              class="text-no-wrap"
            >
              <!-- Dynamic Template for 'date' or 'name' -->
              <template #item.date="{ item }">
                <div>
                  <h6 class="text-h6 text-no-wrap">
                    {{ item.date }} <br />
                    <small>{{ item.time }}</small>
                  </h6>
                </div>
              </template>
              <template #item.name="{ item }">
                <div class="d-flex gap-2 items-center align-center">
                  <VAvatar color="primary" variant="tonal">
                    <VImg :src="item.avatar" />
                  </VAvatar>
                  <div class="text-base text-high-emphasis">
                    {{ item.name }}
                  </div>
                </div>
              </template>

              <!-- Invoice No -->
              <template #item.invoiceNo="{ item }">
                <div v-if="tabData.tag === 'invoice'" class="text-base text-high-emphasis">
                  {{ item.INo }}
                </div>
              </template>

              <!-- Amount -->
              <template #item.amt="{ item }">
                <div v-if="tabData.tag === 'invoice'" class="text-base text-high-emphasis">
                  {{ item.amount }}
                </div>
              </template>

              <!-- Email -->
              <template #item.email="{ item }">
                <div v-if="tabData.tag === 'team'" class="text-base text-high-emphasis">
                  {{ item.email }}
                </div>
              </template>

              <!-- Role -->
              <template #item.role="{ item }">
                <div v-if="tabData.tag === 'team'" class="text-base text-high-emphasis">
                  {{ item.role }}
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
  </VRow>
  <!-- <div>
    <VAlert
      type="error"
      variant="tonal"
    >
      Invoice with ID  {{ route.params.id }} not found!
    </VAlert>
  </div> -->
</template>
