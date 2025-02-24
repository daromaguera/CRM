import { defineStore } from 'pinia';

const handleApiRequest = async (url, method, authHeader, body = null, update5561 = null) => {
    try {
        const options = {
            method,
            headers: { Authorization: `Bearer ${authHeader}` },
            ...(body && { body })
        };
        if(update5561 === null){
            const { data, total, error } = await $api(url, options);
            if (error) return { error };
            return { data, total, error };
        }else if(update5561 === 'update5561'){
            const { error, updated_data, main_data } = await $api(url, options);
            if (error) return { error };
            return { error, updated_data, main_data };
        }
    } catch (error) {
        console.error(`Unexpected error during API request:`, error);
        return { error };
    }
};

export const useDripCampaignStore = defineStore('dripCampaign', {
    state: () => ({
        authHeader: localStorage.getItem('accessToken') || null,
        loadingWhileFetch: false,
        drip_campaigns: [],
        totalDC: 0,
        pageDC: 3,
        // insert more state here...
    }),
    actions: {
        async fetchDC(page, itemPerPage, userType) {
            this.loadingWhileFetch = true;
            const { data, total, error } = await handleApiRequest(`drip-campaigns-info/${page}/${itemPerPage}/${userType}`, 'GET', this.authHeader);
            this.loadingWhileFetch = false;
            if (!error) {
                this.drip_campaigns = data;
                this.totalDC = total;
                return {data,total}
            }else{
                return error;
            }
        },
        async submitNewDC(formData, page, itemPerPage, userType) {
            this.loadingWhileFetch = true;
            const { error, data, total } = await handleApiRequest(`add-drip-campaign/${page}/${itemPerPage}/${userType}`, 'POST', this.authHeader, formData);
            this.loadingWhileFetch = false;
            if (!error) {
                this.drip_campaigns = data;
                this.totalDC = total;
                return {data,total}
            }else{
                return error;
            }
        },
        async searchDC(formData, page, itemPerPage, userType) {
            this.loadingWhileFetch = true;
            const { data, total, error } = await handleApiRequest(`search-drip-campaign/${page}/${itemPerPage}/${userType}`, 'POST', this.authHeader, formData);
            this.loadingWhileFetch = false;
            return {data,total} || error;
        },
        async submitNewEditDC(formData, page, itemPerPage, userType) {
            const { error, updated_data, main_data } = await handleApiRequest(`update-drip-campaign/${page}/${itemPerPage}/${userType}`, 'PUT', this.authHeader, formData, 'update5561');
            if (!error) {
                this.drip_campaigns = main_data.original.data;
                this.totalDC = main_data.original.total;
                return { error, updated_data }
            }else{
                return { error }
            }
        },
    },
});
