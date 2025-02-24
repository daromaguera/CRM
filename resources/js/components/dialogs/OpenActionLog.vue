<script setup>
import avatar1 from '@images/avatars/avatar-1.png'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'submit',
  'update:isDialogVisible',
])

const isUseAsBillingAddress = ref(false)

const onFormSubmit = () => {
  emit('update:isDialogVisible', false)
  // emit('submit', userData.value)
}

const onFormReset = () => {
  emit('update:isDialogVisible', false)
}

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

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
  }
  
  return data[currentTab.value]
})

const search = ref('')

const options = ref({
  itemsPerPage: 5,
  page: 1,
})
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 900"
    :model-value="props.isDialogVisible"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard class="pa-sm-10 pa-2">
      <VCardText>
        <!-- 👉 Title -->
        <h4 class="text-h4 text-center mb-2">
          Action Log
        </h4>
        <p class="text-body-1 text-center mb-6">
          Actions logs are listed below...
        </p>

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

          <!-- Bottom Pagination -->
          <template #bottom>
            <TablePagination
              v-model:page="options.page"
              :items-per-page="options.itemsPerPage"
              :total-items="tabData.data.length"
            />
          </template>
        </VDataTable>

      </VCardText>
    </VCard>
  </VDialog>
</template>
