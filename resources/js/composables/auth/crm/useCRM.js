import { useCRMStore } from '@/stores/crm'

export const formAddContact = {
  id: '',
  fullname: '',
  address: '',
  phone: '',
  email: '',
  yearsInHome: '',
  possibleEquity: '',
  roughCreditScore: '',
  zillowEstimate: '',
}
export const useCRM = () => {
  const crmStore = useCRMStore()
  const fetchCRMBaseInfo = async (page, itemsPerPage, userType) => {
    try {
      const result = await crmStore.fetchCRM(page, itemsPerPage, userType)
      return result
    } catch (error) {
      console.error("Error fetching CRM:", error)
      return null
    }
  }
  const addNewContact = async (formData, page, itemsPerPage, userType) => {
    try {
      const result = await crmStore.addContact(formData, page, itemsPerPage, userType)
      return result
    } catch (error) {
      console.error("Error creating new contact:", error)
      return null
    }
  }
  const searchContact = async (searchData, page, itemsPerPage, userType) => {
    const formData = {
      data_to_search: searchData,
    }
    try {
      const result = await crmStore.searchContact(formData, page, itemsPerPage, userType)
      return result
    } catch (error) {
      console.error("Error searching contacts:", error)
      return null
    }
  }
  const editContact = async (formData, page, itemsPerPage, userType) => {
    try {
      return await crmStore.editContact(formData, page, itemsPerPage, userType)
    } catch (error) {
      console.error("Error creating new contact:", error)
      return null
    }
  }

  return {
    fetchCRMBaseInfo,
    addNewContact,
    searchContact,
    editContact
  }
}
