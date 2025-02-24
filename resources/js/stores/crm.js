import { defineStore } from 'pinia';

const handleApiRequest = async (url, method, authHeader, body = null) => {
    try {
        const options = {
            method,
            headers: { Authorization: `Bearer ${authHeader}` },
            ...(body && { body })
        };
        const { data, total, error, logistics, resultEditedContact } = await $api(url, options);
        if (error) return { error };
        return { data, total, error, logistics, resultEditedContact };
    } catch (error) {
        console.error(`Unexpected error during API request:`, error);
        return { error };
    }
};

export const useCRMStore = defineStore('crm', {
    state: () => ({
        authHeader: localStorage.getItem('accessToken') || null,
        loadingWhileFetch: false,
        crm: [],
        logisticsCRM: [],
        totalCRM: 0,
        pageCRM: 3,
        // insert more state here...
    }),
    actions: {
        async fetchCRM(page, itemPerPage, userType) {
            this.loadingWhileFetch = true;
            const { data, total, error, logistics } = await handleApiRequest(`crm-dashboard-info/${page}/${itemPerPage}/${userType}`, 'GET', this.authHeader);
            if (!error) {
                this.crm = data;
                this.totalCRM = total;
                this.logisticsCRM = logistics;
            }
            this.loadingWhileFetch = false;
            return {data,total,logistics} || error;
        },
        async addContact(formData, page, itemPerPage, userType) {
            this.loadingWhileFetch = true;
            const { data, total, error, logistics } = await handleApiRequest(`add-contact/${page}/${itemPerPage}/${userType}`, 'POST', this.authHeader, formData);
            if (!error) {
                this.crm = data;
                this.totalCRM = total;
                this.logisticsCRM = logistics;
            }
            this.loadingWhileFetch = false;
            return {data,total,logistics} || error;
        },
        async searchContact(formData, page, itemPerPage, userType) {
            this.loadingWhileFetch = true;
            const { data, total, error, logistics } = await handleApiRequest(`search-contact/${page}/${itemPerPage}/${userType}`, 'POST', this.authHeader, formData);
            this.loadingWhileFetch = false;
            return {data,total,logistics} || error;
        },
        async editContact(formData, page, itemPerPage, userType) {
            this.loadingWhileFetch = true;
            const { data, total, error, logistics, resultEditedContact } = await handleApiRequest(`edit-contact/${page}/${itemPerPage}/${userType}`, 'PUT', this.authHeader, formData);
            if (!error) {
                this.crm = data;
                this.totalCRM = total;
                this.logisticsCRM = logistics;
            }
            this.loadingWhileFetch = false;
            return {data, total, error, logistics, resultEditedContact} || error;
        },
    },
});
