<script setup>
import { useTheme } from 'vuetify';

const vuetifyTheme = useTheme()

const getColorInTheme = computed(() => {
  return vuetifyTheme.name.value === 'dark' ? 'white' : '#525050';
});

const search = ref('')

const options = ref({
  itemsPerPage: 5,
  page: 1,
})
const tableHeaders = [
  {
    title: 'TITLE',
    key: 'title',
  },
  {
    title: 'DESCRIPTION',
    key: 'description',
  },
  {
    title: 'UPLOADED DATE',
    key: 'uploadedDate',
  }
]
const tableData = [
  {
    id: 1,
    title: 'Cell Content',
    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sad do eiusmod tempor incidident ut labore et dolore magna aliqua.',
    uploadedDate: '8/10/2024',
  },
  {
    id: 2,
    title: 'Cell Content',
    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sad do eiusmod tempor incidident ut labore et dolore magna aliqua.',
    uploadedDate: '8/10/2024',
  },
  {
    id: 3,
    title: 'Cell Content',
    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sad do eiusmod tempor incidident ut labore et dolore magna aliqua.',
    uploadedDate: '8/10/2024',
  },
  {
    id: 4,
    title: 'Cell Content',
    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sad do eiusmod tempor incidident ut labore et dolore magna aliqua.',
    uploadedDate: '8/10/2024',
  },
  {
    id: 5,
    title: 'Cell Content',
    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sad do eiusmod tempor incidident ut labore et dolore magna aliqua.',
    uploadedDate: '8/10/2024',
  }
]

const tableView = ref(false)
const toggleIconView = () => {
  tableView.value = !tableView.value;
}
</script>

<template>
  <div>
    <div class="pa-4 mt-n4">
      <VBtn size="small" class="rounded-xl float-right">
        <div class="d-flex gap-1"><VIcon icon="mdi-plus" /> Add Contract</div>
      </VBtn>
      <h2>Your Contracts</h2>
    </div>
    <VCard class="pa-6 rounded-xl">
      <VRow>
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
            :style="`color:${getColorInTheme};`"
          >
            <!-- Dynamic Template for 'date' or 'name' -->
            <template #item.title="{ item }">
              <router-link 
                :to="{ 
                  name: 'contracts-sign-id', 
                  params: { id: item.id },
                  query: { 
                    id: item.id,
                    name: item.title,
                  } 
                }"
              >
                <div class="d-flex gap-2">
                  {{ item.title }} <span style="color:#aeaeae;"><i class='bx bx-right-top-arrow-circle'></i></span>
                </div>
              </router-link>
            </template>

            <template #item.description="{ item }">
              <div>
                {{ item.description }}
              </div>
            </template>

            <template #item.uploadedDate="{ item }">
              <div>
                {{ item.uploadedDate }}
              </div>
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
</template>
