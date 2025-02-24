export const useAppointmentStore = defineStore('appointment', () => {
  const calendarAppointments = ref([])

  return { calendarAppointments }
})
