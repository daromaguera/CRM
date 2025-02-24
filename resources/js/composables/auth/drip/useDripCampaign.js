import { useDripCampaignStore } from '@/stores/dripCampaign'

export const formAddDripCamp = {
  id: '',
  dripName: '',
  description: '',
  triggerWords: '',
  firstMessage: '',
  time: '',
}
export const useDripCampaign = () => {
  const dripCampaignStore = useDripCampaignStore()
  const fetchDripCampaignBaseInfo = async (page, itemsPerPage, userType) => {
    try {
      const result = await dripCampaignStore.fetchDC(page, itemsPerPage, userType)
      return result
    } catch (error) {
      console.error("Error fetching Drip Campaigns:", error)
      return null
    }
  }
  const submitNewDripCampaign = async (formData, page, itemsPerPage, userType) => {
    try {
      const result = await dripCampaignStore.submitNewDC(formData, page, itemsPerPage, userType)
      return result
    } catch (error) {
      console.error("Error fetching Drip Campaigns:", error)
      return null
    }
  }
  const searchDripCampaign = async (searchData, page, itemsPerPage, userType) => {
    const formData = {
      data_to_search: searchData,
    }
    try {
      const result = await dripCampaignStore.searchDC(formData, page, itemsPerPage, userType)
      return result
    } catch (error) {
      console.error("Error searching Drip Campaigns:", error)
      return null
    }
  }
  const submitNewEdittedDripCampaign = async (formData, page, itemsPerPage, userType) => {
    try {
      const result = await dripCampaignStore.submitNewEditDC(formData, page, itemsPerPage, userType)
      return result
    } catch (error) {
      console.error("Error while updating Drip Campaigns:", error)
      return null
    }
  }

  return {
    fetchDripCampaignBaseInfo,
    submitNewDripCampaign,
    searchDripCampaign,
    submitNewEdittedDripCampaign
  }
}
