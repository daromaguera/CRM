import { useApi } from '@/composables/useApi';
import { formActionDefault } from '@/utils/constants';
import { ref } from 'vue';
import { toast } from 'vue3-toastify';


// by convention, composable function names start with "use"
export function useTrainingLibrary() {


  const isSaving = ref(false)
  const uploadedVideoFile = ref(null);
  const uploadedThumbnailFile = ref(null);
  const fileVideoPreviewUrl = ref(null);
  const fileThumbnailPreviewUrl = ref(null);
  const isDraggingOver = ref(false);
  const current_page = ref(1);
  const last_page = ref(null);
  const per_page = ref(6);
  const total = ref(null);

  const newTopic = ref('');

  const trainingLibraries = ref([]); // Reactive array to store data
  const trainingLibraryTopics = ref([]);

  // state encapsulated and managed by the composable
  const formDataDefault = {
    title: '',
    tags: [],
    description: '',
    script: '',
    topic_id: '',
    script_file: null,
    tl_video_url: null,
    thumbnail_url: null,
  }
  const formData = ref({ ...formDataDefault })
  const formAction = ref({ ...formActionDefault })
  const refVForm = ref()

    const listForTrainingLibrary = async (current_page, tab) => {
      try {

        // Determine the page number based on the type of current_page
        const pageNumber = typeof current_page === 'object' && current_page !== null
            ? current_page?.value
            : current_page;

          const response = await useApi(`auth/get-training-libraries?page=${pageNumber}&per_page=${per_page.value}&tab=${tab}`, {
              method: 'GET',
          });
          // Store the fetched data in the reactive array
            trainingLibraries.value = (response?.data?._value?.data || []).map((card) => ({
              ...card,
              tags: JSON.parse(card.tags), // Decode JSON string to array
            }));   

            console.log("check training libraries", trainingLibraries);
            
          // Update current_page only if it's an object (like a Vue ref)
          if (typeof current_page === 'object' && current_page !== null) {
              current_page.value = response?.data?._value?.meta?.current_page;
          } else {
            current_page = ref(response?.data?._value?.meta?.current_page);
          }
          
          last_page.value = response?.data?._value?.meta?.last_page;
          total.value = response?.data?._value?.meta?.total;
          per_page.value = response?.data?._value?.meta?.per_page;

      } catch (error) {
          console.error('Error fetching training library list:', error);
      }
    };

    const listForTrainingLibraryTopics = async () => {
      try {

          const response = await useApi(`auth/get-training-library-topics`, {
              method: 'GET',
          });
          // Store the fetched data in the reactive array
          trainingLibraryTopics.value = response?.data?._value?.data; 
            
          console.log("check topics", trainingLibraryTopics);

      } catch (error) {
          console.error('Error fetching training library topics:', error);
      }
    }

    const listForTrainingLibraryTopicsAll = async () => {
      try {

          const response = await useApi(`auth/get-training-library-topics-all`, {
              method: 'GET',
          });
          // Store the fetched data in the reactive array
          trainingLibraryTopics.value = response?.data?._value?.data; 
            
      } catch (error) {
          console.error('Error fetching training library topics:', error);
      }
    }

    const  getTrainingVideoWith = async (value, current_page, aTab) => {

      try {

        // Determine the page number based on the type of current_page
        const pageNumber = typeof current_page === 'object' && current_page !== null
            ? current_page?.value
            : current_page;

          const response = await useApi(`auth/get-training-libraries-with?page=${pageNumber}&per_page=${per_page.value}&filter=${value}&tab=${aTab}`, {
              method: 'GET',
          });
          // Store the fetched data in the reactive array
            trainingLibraries.value = (response?.data?._value?.data || []).map((card) => ({
              ...card,
              tags: JSON.parse(card.tags), // Decode JSON string to array
            }));   
            
          // Update current_page only if it's an object (like a Vue ref)
          if (typeof current_page === 'object' && current_page !== null) {
              current_page.value = response?.data?._value?.meta?.current_page;
          } else {
            current_page = ref(response?.data?._value?.meta?.current_page);
          }
          
          last_page.value = response?.data?._value?.meta?.last_page;
          total.value = response?.data?._value?.meta?.total;
          per_page.value = response?.data?._value?.meta?.per_page;

      } catch (error) {
          console.error('Error fetching user list:', error);
      }
    }
    

    const toggleFavorite = async (tId, cPage, aTab) => {

      try {
        const response = await useApi(`/auth/training-library/${tId}/favorite`, {
          method: 'PATCH',
        });
    
        toast.success(response?.data?._value?.message, toastOptions);

        await listForTrainingLibrary(cPage, aTab);
        
      } catch (error) {
        console.error('Error during adding this to favorite:', error.response || error.message || error);
        toast.error('An error occurred while adding this training library video to favorite!', toastOptions);
      }
    } 
    const toggleDisplay = async (dId) => {

      try {
        const response = await useApi(`/auth/training-library-topic/${dId}/display`, {
          method: 'PATCH',
        });
    
        toast.success(response?.data?._value?.message, toastOptions);

        await listForTrainingLibraryTopicsAll();
        
      } catch (error) {
        console.error('Error during updating the training library display:', error.response || error.message || error);
        toast.error('An error occurred while the training library topic display!', toastOptions);
      }
    } 

    const saveTrainingLibraryTopic = async () => {
      console.log("check topic", newTopic);
    
      try {
        if (!newTopic.value.trim()) {
          toast.error('Topic title cannot be empty!', toastOptions);
          return;
        }
    
        // Create FormData object
        const formData = new FormData();
        formData.append('topic', newTopic.value);
    
        const response = await useApi(`/auth/save-training-library-topic`, {
          method: 'POST',
          body: formData,
        });
    
        console.log("API Response:", response); // Debugging log
    
        if (response?.data?._value?.message) {
          toast.success(response.data._value.message, toastOptions);
        } else {
          toast.success('Topic added successfully!', toastOptions);
        }
    
        newTopic.value = ''; // Reset input field
        await listForTrainingLibraryTopicsAll(); // Refresh topic list
    
      } catch (error) {
        console.error('Error during adding the training library topic:', error.response || error.message || error);
        toast.error('An error occurred while adding the training library topic!', toastOptions);
      }
    };

    const updateTrainingLibraryTopic = async (id, topic) => {
      try {
        if (!newTopic.value.trim()) {
          toast.error('Topic title cannot be empty!', toastOptions);
          return;
        }

        const formData = new FormData();
        formData.append('topic', topic);
    
        const response = await useApi(`/auth/update-training-library-topic/${id}`, {
          method: 'POST',
          body: formData,
        });
        
    
        await listForTrainingLibraryTopicsAll();

        if (response?.data?._value?.message) {
          toast.success(response.data._value.message, toastOptions);
        } 
        return response;
      } catch (error) {
        console.error("Error updating topic:", error);
      }
    };
    
    const deleteTrainingLibraryTopic = async (id) => {
      try {
        const response = await useApi(`/auth/delete-training-library-topic/${id}`, {
          method: 'DELETE',
        });

        if (response?.data?._value?.message) {
          toast.success(response.data._value.message, toastOptions);
        } 

        await listForTrainingLibraryTopicsAll();
      } catch (error) {
        console.error("Error deleting topic:", error);
      }
    };

    const markAsCompleted = async (tId, cPage, aTab) => {
    
      try {
        const response = await useApi(`/auth/training-library/${tId}/complete`, {
          method: 'PATCH',
        });
    
        toast.success(response?.data?._value?.message, toastOptions);

        await listForTrainingLibrary(cPage, aTab);
        
      } catch (error) {
        console.error('Error during marking as completed:', error.response || error.message || error);
        toast.error('An error occurred while marking this training library video to be completed!', toastOptions);
      }
    } 


    const onSubmit = async (activeTab) => {
        try {
          // Reset Form Action utils; Turn on processing at the same time
          formAction.value = { ...formActionDefault, formProcess: true };

          // Validate if the video file is uploaded
          if (!formData.value.tl_video_url || !(formData.value.tl_video_url instanceof File)) {
              toast.error('A video file is required.', toastOptions);
              formAction.value.formProcess = false;
              return;
          }

          if (!formData.value.topic_id) {
            toast.error('Topic is required.', toastOptions);
            formAction.value.formProcess = false;
            return;
        }
      
          // Prepare FormData object for file upload
          const payload = new FormData();
          for (const key in formData.value) {
            if (key === 'tl_video_url' && formData.value[key] instanceof File) {
              payload.append(key, formData.value[key]); // Append the video file
            } else if (key === 'tags') {
              // Serialize the array as JSON
              payload.append('tags', JSON.stringify(formData.value.tags));
            } else {
              payload.append(key, formData.value[key] ?? ""); // Append other fields
            }
          }
      
          console.log('Payload contents:'); // Debugging purpose
          for (const [key, value] of payload.entries()) {
            console.log(`${key}:`, value);
          }
            
          const response = await useApi('/auth/save-training-library', {
            method: 'POST',
            body: payload,
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          });
      
          if (response.response?.value?.ok) {
            console.log('Successfully uploaded:', response.data?._value?.data);
            toast.success('Training Library Successfully Uploaded.', toastOptions);

            const savedData = response.data?._value?.data;

            // Append the newly saved data to trainingLibraries
            if (savedData) {
              trainingLibraries.value.push(savedData);
            }

            await listForTrainingLibrary(current_page, activeTab);
            onFormReset();
          } else {
            console.error('Upload failed:', response.response.statusText);
            toast.error('Failed to upload. Please try again.', toastOptions);
          }
      
        } catch (error) {
          // Handle errors
          console.error('Error during submission:', error.response || error.message || error);
          toast.error('An error occurred while submitting the form.', toastOptions);
      
          // Reset form and processing state
          formAction.value.formProcess = false;
        } finally {
          isSaving.value = false // Set loading state back to false
        }
    }

    const deleteVideo = async (tId, aTab) => {
    
      try {
        const response = await useApi(`/auth/delete-training-library/${tId}`, {
          method: 'DELETE',
        });
    
        toast.success(response?.data?._value?.message, toastOptions);

        await listForTrainingLibrary(current_page?._value, aTab);
        
      } catch (error) {
        console.error('Error during deletion:', error.response || error.message || error);
        toast.error('An error occurred while deleting the training library video!', toastOptions);
      }
    };
    
    const onUpdate = async (tId) => {
      try {
        // Reset Form Action utils; Turn on processing at the same time
        formAction.value = { ...formActionDefault, formProcess: true };
    
        console.log('Form data before submission:', formData.value);
    
        // Prepare FormData object for file upload
        const payload = new FormData();
        for (const key in formData.value) {
          if (key === 'tl_video_url' && formData.value[key] instanceof File) {
            payload.append(key, formData.value[key]); // Append the video file
          } else if (key === 'tags') {
            // Serialize the array as JSON
            payload.append('tags', JSON.stringify(formData.value.tags));
          } else {
            payload.append(key, formData.value[key]); // Append other fields
          }
        }
    
        console.log('Payload contents:'); // Debugging purpose
        for (const [key, value] of payload.entries()) {
          console.log(`${key}:`, value);
        }
    
        console.log('Check payload', payload);
    
        const response = await useApi(`/auth/update-training-library/${tId}`, {
          method: 'POST',
          body: payload,
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        console.log("check the response", response);
    
        if (response.response?.value?.ok) {
          console.log('Successfully uploaded:', response.data?._value?.data);
          toast.success('Training Library Successfully Uploaded.', toastOptions);


          await listForTrainingLibrary(current_page);


          onFormReset();
        } else {
          console.error('Upload failed:', response.response.statusText);
          toast.error('Failed to upload. Please try again.', toastOptions);
        }
    
      } catch (error) {
        // Handle errors
        console.error('Error during submission:', error.response || error.message || error);
        toast.error('An error occurred while submitting the form.', toastOptions);
    
        // Reset form and processing state
        formAction.value.formProcess = false;
      } finally {
        isSaving.value = false // Set loading state back to false
      }
   }

    const allowedVideoTypes = ['video/mp4', 'video/avi', 'video/mov'];

    const allowedThumbnailTypes = ['image/jpeg', 'image/jpg', 'image/png'];


    const handleFileUpload = (event) => {

      const file = event.target.files[0];

      if (!allowedVideoTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a valid video file.', toastOptions);
        return;
      }
      formData.value.tl_video_url = file;
      fileVideoPreviewUrl.value = URL.createObjectURL(file);
      uploadedVideoFile.value = file;
      console.log('Uploaded video file:', file);
    }

    const handleFileScript = (event) => {
      const file = event.target.files[0];
    
      if (!file) return;
    
      const allowedFileTypes = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    
      if (!allowedFileTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a valid Word document (.doc or .docx).', toastOptions);
        return;
      }
    
      formData.value.script_file = file;
      console.log('Uploaded Word document:', file);
    };
    

    const handleThumbnailUpload = (event) => {

        const file = event.target.files[0];
  
        if (!allowedThumbnailTypes.includes(file.type)) {
          toast.error('Invalid file type. Please upload a valid image file.', toastOptions);
          return;
        }
        formData.value.thumbnail_url = file;
        fileThumbnailPreviewUrl.value = URL.createObjectURL(file);
        uploadedThumbnailFile.value = file;
        console.log('Uploaded thumbnail file:', file);
      }
  
    const handleFileDrop = (event) => {
        const file = event.dataTransfer.files[0]; // Get the first dropped file

        if (!allowedVideoTypes.includes(file.type)) {
            toast.error('Invalid file type. Please upload a valid video file.', toastOptions);
            return;
        }

        if (file) {
          handleFileUpload(file);
        }
        isDraggingOver.value = false; // Reset drag-over state
      };
    
      const handleDragOver = (event) => {
        event.preventDefault();
        isDraggingOver.value = true; // Add visual effect
      };
    
      const handleDragLeave = () => {
        isDraggingOver.value = false; // Remove visual effect
      };

      const removeUploadedVideoFile = () => {
        formData.value.tl_video_url = null;
        fileVideoPreviewUrl.value = null;
        uploadedVideoFile.value = null;
        console.log('File removed');
      };

      const removeUploadedThumbnailFile = () => {
        formData.value.thumbnail_url = null;
        uploadedThumbnailFile.value = null;
        fileThumbnailPreviewUrl.value = null;
      };
      
  
      const isVideoFile = (file) => {
        const videoTypes = ['video/mp4', 'video/avi', 'video/mov'];
        return videoTypes.includes(file.type);
      };

      const isImageFile = (file) => {
        const imageTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        return imageTypes.includes(file.type);
      };
      

      // Validate Form and Submit
      const onFormSubmit = (activeTab) => {
        refVForm.value?.validate().then(({ valid }) => {
          if (valid) {
            isSaving.value = true 
            onSubmit(activeTab)
          } 
        })
      }

      const onFormUpdate = (e) => {
        refVForm.value?.validate().then(({ valid }) => {
          if (valid) {
            isSaving.value = true 
            onUpdate(e)
          } 
        })  
      }

 

      const onFormReset = () => {
        // Reset Form Validation State
        refVForm.value?.reset();
      
        // Reset Form Data to Default Values
        formData.value = { ...formDataDefault };
      
        // Reset File Previews and Uploaded Files
        fileVideoPreviewUrl.value = null;
        uploadedVideoFile.value = null;
        fileThumbnailPreviewUrl.value = null;
        uploadedThumbnailFile.value = null;
      
        // Turn off processing
        formAction.value.formProcess = false;
      
        console.log('Form reset successfully');
      }
  

  // expose managed state as return value
  return { current_page, per_page, last_page, total, formData, formDataDefault, formAction, refVForm, trainingLibraryTopics, newTopic, listForTrainingLibraryTopicsAll, deleteTrainingLibraryTopic, updateTrainingLibraryTopic, saveTrainingLibraryTopic, toggleDisplay, listForTrainingLibraryTopics, getTrainingVideoWith, markAsCompleted, deleteVideo, onFormUpdate, onFormSubmit, handleFileUpload, handleFileScript, handleThumbnailUpload, removeUploadedVideoFile, removeUploadedThumbnailFile, isImageFile, isVideoFile, handleFileDrop, handleDragOver, handleDragLeave, fileThumbnailPreviewUrl, uploadedThumbnailFile, isSaving, fileVideoPreviewUrl, uploadedVideoFile, isDraggingOver, trainingLibraries, listForTrainingLibrary, toggleFavorite  }
}
