import { defineStore } from 'pinia';

const handleApiRequest = async (url, method, authHeader, body = null) => {
    try {
        const options = {
            method,
            headers: { Authorization: `Bearer ${authHeader}` },
            ...(body && { body })
        };
        const { data, tomorrow, today, error, contacts } = await $api(url, options);
        if (error) return { error };
        return { data, tomorrow, today, contacts };
    } catch (error) {
        console.error(`Unexpected error during API request:`, error);
        return { error };
    }
};

export const useCalendarStore = defineStore('calendar', {
    state: () => ({
        availableCalendars: [
            { color: 'error', label: 'Personal' },
            { color: 'primary', label: 'Business' },
            { color: 'warning', label: 'Family' },
            { color: 'success', label: 'Holiday' },
            { color: 'info', label: 'ETC' },
        ],
        authUserData: JSON.parse(localStorage.getItem('userData')) || null,
        userDefaultTimeZone: JSON.parse(localStorage.getItem('userData')),
        authHeader: localStorage.getItem('accessToken') || null,
        loadingWhileFetch: false,
        appointmentsData: [],
        appointmentsTomorrowData: [],
        appointmentsTodayData: [],
        contactsList: [],
        submitted: false,
    }),
    actions: {
        async fetchEvents() {
            this.loadingWhileFetch = true;
            const { data, tomorrow, today, error, contacts } = await handleApiRequest('appointment', 'GET', this.authHeader);
            console.log("Fetched contacts: ", contacts);
            if (!error) {
                this.appointmentsData = data;
                this.appointmentsTomorrowData = tomorrow;
                this.appointmentsTodayData = today;
                this.contactsList = contacts;
            }
            this.loadingWhileFetch = false;
            return data || error;
        },

        async saveEvent(event, method) {
            this.loadingWhileFetch = true;
            const { data, tomorrow, today, error, contacts } = await handleApiRequest('appointment', method, this.authHeader, event);
            if (!error) {
                if(method === 'POST'){
                    this.appointmentsData = data;
                }else if(method === 'PUT'){
                    const index = this.appointmentsData.findIndex(item => item.id === data[0].id);
                    if (index !== -1) {
                        this.appointmentsData[index] = { ...this.appointmentsData[index], selectedContacts: data[0].selectedContacts };
                    }
                }
                this.appointmentsTomorrowData = tomorrow;
                this.appointmentsTodayData = today;
                this.contactsList = contacts;
            }
            this.loadingWhileFetch = false;
            return data || error;
        },

        async addEvent(event) {
            return this.saveEvent(event, 'POST');
        },

        async updateEvent(event) {
            return this.saveEvent(event, 'PUT');
        },

        async removeEvent(eventId) {
            return await handleApiRequest(`/apps/calendar/${eventId}`, 'DELETE', this.authHeader);
        }
    },
});
