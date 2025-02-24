<script setup>
import avatar1 from '@images/avatars/avatar-1.png'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { VForm } from 'vuetify/components/VForm'
import { useCalendarStore } from './useCalendarStore'

// 👉 store
const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  updateTimezoneDialog: {
    type: Boolean,
    required: true,
  },
  event: {
    type: null,
    required: true,
  },
  user_default_timezone: {
    type: null,
    required: true,
  }
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'update:updateTimezoneDialog',
  'addEvent',
  'updateEvent',
  'removeEvent',
])

const store = useCalendarStore()
const refForm = ref()

const participants_items = computed(() => store.contactsList);
const selectedContacts = ref([])

const timezone_list = [
  'America/New_York',
  'America/Chicago',
  'America/Denver',
  'America/Los_Angeles',
  'America/Phoenix',
  'America/Anchorage',
  'America/Honolulu',
]

// 👉 Event
const event = ref({
  ...JSON.parse(JSON.stringify(props.event)),
  timezone: props.user_default_timezone,
  new_detected_timezone: Intl.DateTimeFormat().resolvedOptions().timeZone, // Auto-detect user's timezone
})

const scrollbarRef = ref(null)

const resetEvent = () => {
  if (scrollbarRef.value) {
    scrollbarRef.value.$el.scrollTop = 0 // Reset scroll to the top
  }
  event.value = {
    ...JSON.parse(JSON.stringify(props.event)),
    //timezone: props.user_default_timezone,
    new_detected_timezone: Intl.DateTimeFormat().resolvedOptions().timeZone, // Auto-detect user's timezone
  }
  selectedContacts.value = []
  selectedContacts.value = event.value.extendedProps?.selectedContacts
  nextTick(() => {
    refForm.value?.resetValidation()
  })
}

watch(() => props.isDrawerOpen, resetEvent)

const convertElements = (data) => {
  const newData = {
    ...data.value,
    selectedContacts: selectedContacts.value,
    //timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
  }
  
  // Convert time to 24-hour format correctly (removed 'this')
  if(newData.allDay){
    newData.timeStart = "22:00";
    newData.timeEnd = "23:00";
  }else{
    newData.timeStart = convertTo24Hour(newData.timeStart);
    newData.timeEnd = convertTo24Hour(newData.timeEnd);
  }

  return newData
}

const handleSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      // If id exists => Update event
      if (event.value.id !== '') {
        var wTZdata = convertElements(event)
        emit('updateEvent', wTZdata)
        // Close drawer
        emit('update:isDrawerOpen', false)
      } 
      // Else => Add new event with timezone and converted time
      else {
        var wTZdata = convertElements(event)
        emit('addEvent', wTZdata)
        if(store.submitted){
          // Close drawer
          emit('update:isDrawerOpen', false)
          store.submitted = false;
        }
      }
    }
  })
}

const convertTo24Hour = (time) => {
    if (!time) return time;

    // Regex to match the time in 12-hour format (e.g., 2:00 PM or 02:00 PM)
    const match = time.match(/(\d{1,2}):(\d{2})\s?(AM|PM)?/i);
    
    if (!match) {
        console.error("Invalid time format:", time);
        return time;
    }

    let [ , hours, minutes, period ] = match;
    hours = parseInt(hours);
    minutes = parseInt(minutes);

    // Adjust the hour conversion correctly
    if (period?.toUpperCase() === "PM") {
        if (hours < 12) {
            hours += 12;
        }
    } else if (period?.toUpperCase() === "AM" && hours === 12) {
        hours = 0;
    }

    // Ensure hours don't exceed 23
    hours = Math.min(hours, 23);
    minutes = Math.min(minutes, 59);

    // Return the formatted time in 24-hour format
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
};

// 👉 Form
const onCancel = () => {

  // Close drawer
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    resetEvent()
    refForm.value?.resetValidation()
  })
}

const dialogModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}
const UpdateTimezoneSettings = () => {
  emit('update:updateTimezoneDialog', true)
}
</script>

<template>
  <VNavigationDrawer
    temporary
    location="end"
    :model-value="props.isDrawerOpen"
    width="370"
    :border="0"
    class="scrollable-content"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- 👉 Header -->
    <AppDrawerHeaderSection
      :title="event.id ? 'Update Appointment' : 'Add Appointment'"
      @cancel="$emit('update:isDrawerOpen', false)"
    >
      <template #beforeClose>
        <!-- <IconBtn
          v-show="event.id"
          @click="removeEvent"
        >
          <VIcon
            size="18"
            icon="bx-trash"
          />
        </IconBtn> -->
      </template>
    </AppDrawerHeaderSection>

    <VDivider />

    <PerfectScrollbar ref="scrollbarRef" :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- SECTION Form -->
          <VForm
            ref="refForm"
            @submit.prevent="handleSubmit"
          >
            <VRow>
              <!-- 👉 Title -->
              <VCol cols="12">
                <AppTextField
                  v-model="event.title"
                  label="Name *"
                  placeholder="Type the event name..."
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <span class="float-right"><small><span style="color:#949292;">Default: <b>{{ props.user_default_timezone }}</b></span> <VIcon icon="mdi-account-settings-variant" @click="UpdateTimezoneSettings" class="ml-2 mt-n1 cursor-pointer"></VIcon></small></span><br/>
                <AppSelect
                  v-model="event.timezone"
                  :items="timezone_list"
                  append-icon="mdi-timetable"
                  label="Appointment Timezone"
                  single-line
                  variant="filled"
                  placeholder="Select State"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- 👉 Date -->
              <VCol cols="12">
                <AppDateTimePicker
                  v-model="event.date_start"
                  label="Date Start *"
                  :config="{ enableTime: false, noCalendar: false, dateFormat: 'Y-m-d' }"
                  placeholder="Select date"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- 👉 Start Time -->
              <VCol cols="12">
                <h4>TIME</h4>
                <VRow class="pa-2">
                  <VCol cols="12">
                    <VSwitch
                      v-model="event.allDay"
                      label="All Day?"
                    />
                  </VCol>
                </VRow>
                <div v-if="!event.allDay">
                  <div class="pa-2"><h4>OR</h4></div>
                  <VRow class="pa-2">
                    <VCol cols="12">
                      <AppDateTimePicker
                        v-model="event.timeStart"
                        label="Start *"
                        placeholder="Select time"
                        :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i K', altInput: true, altFormat: 'h:i K' }"
                        :rules="[requiredValidator]"
                      />
                    </VCol>

                    <!-- End Time -->
                    <VCol cols="12 mt-n3">
                      <AppDateTimePicker
                        v-model="event.timeEnd"
                        label="End *"
                        placeholder="Select time"
                        :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i K', altInput: true, altFormat: 'h:i K' }"
                        :rules="[requiredValidator]"
                      />
                    </VCol>
                  </VRow>
                </div>
              </VCol>

              <VCol cols="12">
                <VBtn size="small" class="rounded-xl" @click="isEventHandlerSidebarActive = true"><VIcon icon="mdi-plus" /> Add Drip Campaign</VBtn>
              </VCol>

              <!-- 👉 Comments -->
              <VCol cols="12">
                <h5>Comments * </h5>
                <TiptapEditor
                  v-model="event.extendedProps.comments"
                  class="border rounded basic-editor"
                />
              </VCol>

              <!-- 👉 Participants -->
              <VCol cols="12">
                <h5>Participant/s: </h5>
                <AppSelect
                    v-model="selectedContacts"
                    :items="participants_items"
                    item-title="complete_name"
                    item-value="id"
                    placeholder="Select Item"
                    multiple
                    clearable
                    clear-icon="bx-x"
                  >
                  <template #selection="{ item }">
                    <VChip>
                      <template #prepend>
                        <VAvatar
                          start
                          :image="avatar1"
                        />
                      </template>

                      <span>{{ item.title }}</span>
                    </VChip>
                  </template>
                </AppSelect>
              </VCol>

              <!-- 👉 Form buttons -->
              <VCol cols="12" class="mt-10">
                <VBtn
                  type="submit"
                  class="me-3"
                >
                  Save
                </VBtn>
                <VBtn
                  variant="outlined"
                  color="secondary"
                  @click="onCancel"
                >
                  Cancel
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        <!-- !SECTION -->
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>

<style lang="scss">
.basic-editor {
  .ProseMirror {
    block-size: 200px;
    outline: none;
    overflow-y: auto;
    padding-inline: 0.5rem;
  }
}
</style>
