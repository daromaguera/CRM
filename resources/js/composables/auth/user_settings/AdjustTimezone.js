import { defineStore } from 'pinia';

const handleApiRequest = async (url, method, authHeader, body = null) => {
    try {
        const options = {
            method,
            headers: { Authorization: `Bearer ${authHeader}` },
            ...(body && { body })
        };
        const { data, error } = await $api(url, options);
        if (error) return { error };
        return { data };
    } catch (error) {
        console.error(`Unexpected error during API request:`, error);
        return { error };
    }
};

export const useAdjustTimezoneStore = defineStore('adjustTimezone', {
    state: () => ({
        userDefaultTimeZone: JSON.parse(localStorage.getItem('userData')),
        authHeader: localStorage.getItem('accessToken') || null,
    }),
    actions: {
        // async fetchEvents() {
        //     this.loadingWhileFetch = true;
        //     const { data, tomorrow, today, error } = await handleApiRequest('appointment', 'GET', this.authHeader);
        //     console.log("Data: ", data);
        //     console.log("Today: ", today);
        //     console.log("Tomorrow: ", tomorrow);
        //     if (!error) {
        //         this.appointmentsData = data;
        //         this.appointmentsTomorrowData = tomorrow;
        //         this.appointmentsTodayData = today;
        //     }
        //     this.loadingWhileFetch = false;
        //     return data || error;
        // },
        async saveNewTimezone(formData, method) {
            const { data, error } = await handleApiRequest('update-timezone', method, this.authHeader, formData);
            if (!error) {
                console.log(data[0].timezone)
            }
            return data[0].timezone || error;
        },
        async updateTimezone(newTimezone) {
            const data = { timezone: newTimezone };
            return this.saveNewTimezone(data, 'PUT')
        },
    },
});
