<script setup>
import FullCalendar from '@fullcalendar/vue3'
import {
  blankEvent,
  useCalendar,
} from '@/views/apps/calendar/useCalendar'
import { useCalendarStore } from '@/views/apps/calendar/useCalendarStore'

// Components
import CalendarEventHandler from '@/views/apps/calendar/CalendarEventHandler.vue'
import UpdateTimezoneSettings from '@/components/dialogs/UpdateTimezoneSettings.vue';

const user_default_timezone = ref(null);

// 👉 Store
const store = useCalendarStore()

// 👉 Event
const event = ref(structuredClone(blankEvent))
const isEventHandlerSidebarActive = ref(false)

watch(isEventHandlerSidebarActive, val => {
  if (!val){
    event.value = structuredClone(blankEvent)
  }
})

const { isLeftSidebarOpen } = useResponsiveLeftSidebar()

// 👉 useCalendar
const { refCalendar, calendarOptions, addEvent, updateEvent, removeEvent, jumpToDate } = useCalendar(event, isEventHandlerSidebarActive, isLeftSidebarOpen)

// const jumpToDateFn = date => {
//   jumpToDate(date)
// }

const tomorrow = [
  { title: 'Consult with Jenson', text: 'No content yet...' },
  { title: 'Another Panel', text: 'More content...' },
  { title: 'Another Panel', text: 'More content...' },
  { title: 'Another Panel', text: 'More content...' },
  { title: 'Another Panel', text: 'More content...' },
];

const formatDate = (date, timezone) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('en-US', 
    //{ timeZone: timezone }
  );
};
const formatTime = (date, timezone) => {
  if (!date) return '';
  return new Date(date).toLocaleTimeString('en-US', {
    //timeZone: timezone,
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  });
};

// 👉 onMounted
onMounted(() => {
  nextTick(() => { // Should not be undefined now
    const storedData = localStorage.getItem('userData');
    if (storedData) {
      const userData = JSON.parse(storedData);
      user_default_timezone.value = userData?.timezone || 'UTC'; // Access the timezone property
      event.timezone = user_default_timezone.value

      // if (user_default_timezone !== Intl.DateTimeFormat().resolvedOptions().timeZone) {
      //   alert("A different timezone has been detected!")
      // }
    }
  })
})

// User Settings Dialog
const updateTimezoneDialog = ref(true);
</script>

<template>
  <div class="pa-4 mt-n4">
    <VBtn size="small" color="secondary" class="rounded-xl float-right ml-2" @click="updateTimezoneDialog = true"><VIcon icon="mdi-account-settings-variant" /></VBtn>
    <VBtn size="small" class="rounded-xl float-right" @click="isEventHandlerSidebarActive = true"><VIcon icon="mdi-plus" /> New Appointment</VBtn>
    <h2>Appointments</h2>
  </div>
  <VRow>
    <VCol cols="12" lg="3" md="3" sm="3" xs="3" v-if="store.appointmentsTodayData.length > 0 || store.appointmentsTomorrowData.length > 0">
      <VCard :loading="store.loadingWhileFetch">
        <div v-if="store.appointmentsTodayData.length > 0">
          <VCardText class="headingTitle">Today</VCardText>
          <v-expansion-panels variant="accordion" class="mt-n2">
            <v-expansion-panel v-for="(item, index) in store.appointmentsTodayData" :key="index">
              <template #title>
                <div class="" style="line-height:18px;">
                  <b>{{ item.title }}</b><br/>
                  <div class="textBelowTitle">
                    {{ formatDate(item.date_start, item.timezone) }} 
                    <small v-if="item.allDay">
                      <VChip
                        label
                        color="info"
                        style="padding:1px;font-size:11px;margin-left:5px;padding-left:8px;padding-right:8px;"
                      >
                        All Day
                      </VChip>
                    </small>
                    <small v-else>{{ formatTime(item.date_start, item.timezone) }}</small> 
                  </div>
                </div>
              </template>
              <template #text>
                <div v-html="item.comments" style="margin:0;line-height:0;"></div>
              </template>
            </v-expansion-panel>
          </v-expansion-panels>
        </div>
        <div :class="store.appointmentsTomorrowData.length > 0 ? '' : 'mt-4'" v-if="store.appointmentsTomorrowData.length > 0">
          <VCardText class="headingTitle">Tomorrow</VCardText>
          <v-expansion-panels variant="accordion" class="mt-n2">
            <v-expansion-panel v-for="(item, index) in store.appointmentsTomorrowData" :key="index">
              <template #title>
                <div class="" style="line-height:18px;">
                  <b>{{ item.title }}</b><br/>
                  <div class="textBelowTitle">
                    {{ formatDate(item.date_start, item.timezone) }} 
                    <small v-if="item.allDay">
                      <VChip
                        label
                        color="info"
                        style="padding:1px;font-size:11px;margin-left:5px;padding-left:8px;padding-right:8px;"
                      >
                        All Day
                      </VChip>
                    </small>
                    <small v-else>{{ formatTime(item.date_start, item.timezone) }}</small> 
                  </div>
                </div>
              </template>
              <template #text>
                <div v-html="item.comments"></div>
              </template>
            </v-expansion-panel>
          </v-expansion-panels>
        </div>
      </VCard>
    </VCol>
    <VCol 
    cols="12"
    :lg="(store.appointmentsTodayData.length > 0 || store.appointmentsTomorrowData.length > 0) ? 9 : 12"
    :md="(store.appointmentsTodayData.length > 0 || store.appointmentsTomorrowData.length > 0) ? 9 : 12"
    :sm="(store.appointmentsTodayData.length > 0 || store.appointmentsTomorrowData.length > 0) ? 9 : 12"
    :xs="(store.appointmentsTodayData.length > 0 || store.appointmentsTomorrowData.length > 0) ? 9 : 12"
    >
      <VMain>
        <VCard flat class="pa-2 cursor-pointer" :loading="store.loadingWhileFetch">
          <FullCalendar
            ref="refCalendar"
            :options="calendarOptions"
          />
        </VCard>
      </VMain>
    </VCol>
    <CalendarEventHandler
      v-model:updateTimezoneDialog="updateTimezoneDialog"
      v-model:isDrawerOpen="isEventHandlerSidebarActive"
      :event="event"
      :user_default_timezone="user_default_timezone"
      @add-event="addEvent"
      @update-event="updateEvent"
      @remove-event="removeEvent"
    />
  </VRow>
  <UpdateTimezoneSettings v-model:isDialogVisible="updateTimezoneDialog" :user_default_timezone="user_default_timezone" />
</template>

<style lang="scss">
@use "@core-scss/template/libs/full-calendar";

.calendars-checkbox {
  .v-label {
    color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
    opacity: var(--v-high-emphasis-opacity);
  }
}

.calendar-add-event-drawer {
  &.v-navigation-drawer:not(.v-navigation-drawer--temporary) {
    border-end-start-radius: 0.375rem;
    border-start-start-radius: 0.375rem;
  }

  &.v-navigation-drawer--temporary:not(.v-navigation-drawer--active) {
    transform: translateX(-110%) !important;
  }
}

.calendar-date-picker {
  display: none;

  +.flatpickr-input {
    +.flatpickr-calendar.inline {
      border: none;
      box-shadow: none;

      .flatpickr-months {
        border-block-end: none;
      }
    }
  }

  & ~ .flatpickr-calendar .flatpickr-weekdays {
    margin-block: 0 4px;
  }
}

@media screen and (max-width: 1279px) {
  .calendar-add-event-drawer {
    border-width: 0;
  }
}
</style>

<style lang="scss" scoped>
.v-layout {
  overflow: visible !important;

  .v-card {
    overflow: visible;
  }
}
.comments-wrapper p {
    margin: 0;             /* Completely remove margin */
    padding: 0;            /* Remove padding */
    line-height: 1.2;      /* Adjust line height for better readability */
    margin-bottom: 0.5rem; /* Optional: Slight spacing between paragraphs */
}
</style>
