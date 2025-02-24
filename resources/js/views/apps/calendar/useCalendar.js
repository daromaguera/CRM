import { store } from '@/plugins/2.pinia'
import { useCalendarStore } from '@/views/apps/calendar/useCalendarStore'
import { useCRMStore } from '@/stores/crm'
import { useConfigStore } from '@core/stores/config'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import listPlugin from '@fullcalendar/list'
import timeGridPlugin from '@fullcalendar/timegrid'
import { nextTick } from 'vue'

const getStoreTimeZone = useCalendarStore()

export const blankEvent = {
  id: '',
  title: '',
  date_start: '',
  timeStart: '',
  timeEnd: '',
  timezone: getStoreTimeZone.userDefaultTimeZone.timezone,
  new_detected_timezone: '',
  allDay: false,
  extendedProps: {
    /*
          ℹ️ We have to use undefined here because if we have blank string as value then select placeholder will be active (moved to top).
          Hence, we need to set it to undefined or null
        */
    calendar: undefined,
    guests: [],
    location: '',
    comments: '',
    selectedContacts: [],
  },
}
export const useCalendar = (event, isEventHandlerSidebarActive, isLeftSidebarOpen) => {
  const configStore = useConfigStore()
  const crmStore = useCRMStore();

  // 👉 Storee
  const store = useCalendarStore()

  // 👉 Calendar template ref
  const refCalendar = ref()


  // 👉 Calendar colors
  // const calendarsColor = {
  //   Business: 'primary',
  //   Holiday: 'success',
  //   Personal: 'error',
  //   Family: 'warning',
  //   ETC: 'info',
  // }

  // ℹ️ Extract event data from event API
  const extractEventDataFromEventApi = eventApi => {
    const { publicId, title, allDay } = eventApi._def;
    const { start, end } = eventApi._instance.range;
    const id = publicId;
    // Extract date in YYYY-MM-DD format
    var date_start = start.toISOString().split('T')[0];

    // Function to convert to 12-hour format with AM/PM
    const formatTime = (date) => {
        const options = { hour: '2-digit', minute: '2-digit', hour12: true, timeZone: 'UTC' };
        return new Intl.DateTimeFormat('en-US', options).format(date);
    };

    // Extract timeStart and timeEnd in 12-hour format
    var timeStart = formatTime(start);
    var timeEnd = formatTime(end);

    // Destructure extendedProps safely
    const { calendar, guests, location, comments, timezone, uc_end, uc_start, selectedContacts } = eventApi.extendedProps || {};

    if(timezone !== store.userDefaultTimeZone.timezone){
      const conv_uc_start = new Date(uc_start);
      const conv_uc_end = new Date(uc_end);
      date_start = conv_uc_start.toISOString().split('T')[0];
      timeStart = conv_uc_start.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      });
      timeEnd = conv_uc_end.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      });
    }

    return {
        id,
        title,
        date_start,         // Only date in YYYY-MM-DD format
        timeStart,         // Time in 12-hour format
        timeEnd,           // Time in 12-hour format
        timezone,
        extendedProps: {
            calendar,
            guests,
            location,
            comments,
            selectedContacts,
        },
        allDay,
    };
  }
    
  if (typeof process !== 'undefined' && process.server)
    store.fetchEvents()

  let isLoading = false; // Initialize loading state

  // 👉 Fetch events
  const fetchEvents = (info, successCallback) => {
    // If there's no info => Don't make useless API call
    if (!info) return;

    if (store.appointmentsData.length === 0) {
      // Set loading state to true and prevent calendar display
      isLoading = true;
      updateLoadingState();

      store.fetchEvents()
        .then(r => {
          successCallback(r.map(e => ({
            id: e.id,
            title: e.title, // Ensure to use `title` if you're using FullCalendar
            allDay: e.allDay,

            // Ensure the date conversion is correct
            start: new Date(e.date_start),
            end: new Date(e.date_end),

            extendedProps: {
              calendar: e.extendedProps?.calendar,
              guests: e.extendedProps?.guests,
              location: e.extendedProps?.location,
              comments: e.comments,  // `comments` is directly available in your data
              timezone: e.timezone,
              uc_start: new Date(e.uc_date_start),
              uc_end: new Date(e.uc_date_end),
              selectedContacts: e.selectedContacts,
            }
          })))
        })
        .catch(e => {
          console.error('Error occurred while fetching calendar events', e)
        })
        .finally(() => {
          // Set loading state to false after the fetch is complete
          isLoading = false;
          updateLoadingState();
        });
    }else{
      successCallback(store.appointmentsData.map(e => ({
        id: e.id,
        title: e.title, 
        allDay: e.allDay,

        start: new Date(e.date_start),
        end: new Date(e.date_end),

        extendedProps: {
          calendar: e.extendedProps?.calendar,
          guests: e.extendedProps?.guests,
          location: e.extendedProps?.location,
          comments: e.comments,
          timezone: e.timezone,
          uc_start: new Date(e.uc_date_start),
          uc_end: new Date(e.uc_date_end),
          selectedContacts: e.selectedContacts,
        }
      })));
    }
  }

  // Function to toggle calendar visibility and loading state
  const updateLoadingState = () => {
    const loadingElement = document.getElementById('loading-indicator');
    const calendarElement = document.getElementById('calendar-container');
    
    if (loadingElement && calendarElement) {
        if (isLoading) {
            loadingElement.style.display = 'block';
            calendarElement.style.display = 'none';
        } else {
            loadingElement.style.display = 'none';
            calendarElement.style.display = 'block';
        }
    }
  };


  // 👉 Calendar API
  const calendarApi = ref(null)


  // 👉 Update event in calendar [UI]
  const updateEventInCalendar = (updatedEventData) => {
    if(updatedEventData.length === 0){
      store.fetchEvents()
    }else{
      const existingEvent = calendarApi.value?.getEventById(String(updatedEventData[0].id))
      if (!existingEvent) {
        console.warn('Can\'t found event in calendar to update')
        
        return
      }

      // console.log("Existing Event: ", existingEvent)
      // console.log("New Event: ", updatedEventData)

      // ---Set event properties except date related
      // Docs: https://fullcalendar.io/docs/Event-setProp
      // dateRelatedProps => ['start', 'end', 'allDay']
      existingEvent.setProp('title', updatedEventData[0].title)

      // --- Set date related props
      // ? Docs: https://fullcalendar.io/docs/Event-setDates
      //existingEvent.setDates(updatedEventData[0].date_start, updatedEventData[0].date_end, { allDay: updatedEventData[0].allDay })

      const startDate = new Date(updatedEventData[0].date_start);
      const endDate = new Date(updatedEventData[0].date_end);

      // Adjust end date if allDay is true and start and end are the same
      if (updatedEventData[0].allDay) {
          endDate.setDate(endDate.getDate() + 1);
      }

      existingEvent.setDates(startDate, endDate, { allDay: updatedEventData[0].allDay });

      // --- Set event's extendedProps
      // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
      // for (let index = 0; index < extendedPropsToUpdate.length; index++) {
      //   const propName = extendedPropsToUpdate[index]

      existingEvent.setExtendedProp('comments', updatedEventData[0].comments)
      existingEvent.setExtendedProp('selectedContacts', updatedEventData[0].selectedContacts)
      // }
    }
  }


  // 👉 Remove event in calendar [UI]
  const removeEventInCalendar = eventId => {
    const _event = calendarApi.value?.getEventById(eventId)
    if (_event)
      _event.remove()
  }


  // 👉 refetch events
  const refetchEvents = () => {
    calendarApi.value?.refetchEvents()
  }

  //watch(() => store.selectedCalendars, refetchEvents)
  const userData = JSON.parse(localStorage.getItem('userData'));

  // 👉 Add event
  const addEvent = (_event) => {
    // ✅ Convert the time strings into Date objects for proper comparison
    const startTime = new Date(`${_event.date_start}T${_event.timeStart}`);
    const endTime = new Date(`${_event.date_start}T${_event.timeEnd}`);

    // ✅ Check if timeEnd is before timeStart
    if (endTime <= startTime) {
        alert("End time must be after the Start Time or must be greater-than the Start time.");
        store.submitted = false;
        return; // Stop the function execution if invalid
    }else{
      store.submitted = true;
      store.addEvent(_event)
        .then(() => {
          refetchEvents()
          crmStore.fetchCRM(1,10,userData.user_role_id)
        })
        .catch(error => {
          console.error("Error adding event:", error);
        });
    }
  }


  // 👉 Update event
  const updateEvent = _event => {
    // ℹ️ Making API call using $api('', { method: ... })
    store.updateEvent(_event)
      .then(r => {
        // const propsToUpdate = ['id', 'title']
        // const extendedPropsToUpdate = ['comments']
        updateEventInCalendar(r)
      })
    refetchEvents()
  }


  // 👉 Remove event
  const removeEvent = eventId => {
    store.removeEvent(eventId).then(() => {
      removeEventInCalendar(eventId)
    })
  }

  // 👉 Calendar options [[ YOU CAN MAKE THIS AS STATE ]]
  const calendarOptions = {
    //timeZone: store.userDefaultTimeZone.timezone,
    plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
      start: 'drawerToggler,prev,next title',
      end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
    },
    events: fetchEvents,
    eventTimeFormat: { // Ensure proper format
        hour: 'numeric',
        meridiem: 'short'
    },

    // ❗ We need this to be true because when its false and event is allDay event and end date is same as start data then Full calendar will set end to null
    forceEventDuration: true,

    /*
      Enable dragging and resizing event
      Docs: https://fullcalendar.io/docs/editable
    */
    editable: true,

    /*
      Enable resizing event from start
      Docs: https://fullcalendar.io/docs/eventResizableFromStart
    */
    eventResizableFromStart: true,

    /*
      Automatically scroll the scroll-containers during event drag-and-drop and date selecting
      Docs: https://fullcalendar.io/docs/dragScroll
    */
    dragScroll: true,

    /*
      Max number of events within a given day
      Docs: https://fullcalendar.io/docs/dayMaxEvents
    */
    dayMaxEvents: 2,

    /*
        Determines if day names and week names are clickable
        Docs: https://fullcalendar.io/docs/navLinks
      */
    navLinks: true,
    // eventClassNames({ event: calendarEvent }) {
    //   const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar]
      
    //   return [
    //     // Background Color
    //     `bg-light-${colorName} text-${colorName}`,
    //   ]
    // },
    eventClick({ event: clickedEvent, jsEvent }) {
      // Prevent the default action
      jsEvent.preventDefault()
      // * Only grab required field otherwise it goes in infinity loop
      // ! Always grab all fields rendered by form (even if it get `undefined`) otherwise due to Vue3/Composition API you might get: "object is not extensible"
      event.value = extractEventDataFromEventApi(clickedEvent)
      isEventHandlerSidebarActive.value = true
    },

    // customButtons
    dateClick(info) {
      event.value = { 
        ...event.value, 
        timezone: store.userDefaultTimeZone.timezone,
        date_start: info.date 
      }
      isEventHandlerSidebarActive.value = true
    },

    /*
      Handle event drop (Also include dragged event)
      Docs: https://fullcalendar.io/docs/eventDrop
      We can use `eventDragStop` but it doesn't return updated event so we have to use `eventDrop` which returns updated event
    */
    eventDrop({ event: droppedEvent }) {
      updateEvent(extractEventDataFromEventApi(droppedEvent))
    },

    /*
      Handle event resize
      Docs: https://fullcalendar.io/docs/eventResize
    */
    eventResize({ event: resizedEvent }) {
      if (resizedEvent.start && resizedEvent.end)
        updateEvent(extractEventDataFromEventApi(resizedEvent))
    },
    customButtons: {
      drawerToggler: {
        text: 'calendarDrawerToggler',
        click() {
          isLeftSidebarOpen.value = true
        },
      },
    },
  }


  // 👉 onMounted
  onMounted(() => {
    nextTick(() => { // Should not be undefined now
      calendarApi.value = refCalendar.value.getApi()
    })
  })


  // 👉 Jump to date on sidebar(inline) calendar change
  const jumpToDate = currentDate => {
    calendarApi.value?.gotoDate(new Date(currentDate))
  }

  watch(() => configStore.isAppRTL, val => {
    calendarApi.value?.setOption('direction', val ? 'rtl' : 'ltr')
  }, { immediate: true })
  
  return {
    refCalendar,
    calendarOptions,
    refetchEvents,
    fetchEvents,
    addEvent,
    updateEvent,
    removeEvent,
    jumpToDate,
  }
}
