<script setup>
import { useTrainingLibrary } from '@/composables/training_library/training_library'
import { ref, watch } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { useTheme } from 'vuetify'
import { VForm } from 'vuetify/components/VForm'

const theme = useTheme()
const newVideoFile = ref(false)
const newImageFile = ref(false)
const isScriptFile = ref(false)

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  editTrainingLibrary: {
    type: Object,
    required: true,
  },
  activeTab: {
    type: String,
    required: true,
  },
})

const {
  trainingLibraries,
  refVForm,
  formData,
  onFormUpdate,
  onFormSubmit,
  handleFileUpload,
  handleFileScript,
  handleThumbnailUpload,
  removeUploadedVideoFile,
  removeUploadedThumbnailFile,
  isVideoFile,
  isImageFile,
  handleFileDrop,
  handleDragOver,
  handleDragLeave,
  fileThumbnailPreviewUrl,
  uploadedThumbnailFile,
  isSaving,
  fileVideoPreviewUrl,
  uploadedVideoFile,
  isDraggingOver,
  trainingLibraryTopics,
  listForTrainingLibraryTopics,
} = useTrainingLibrary()

// Watch the .value of trainingLibraries
watch(
  trainingLibraries,
  (newValue, oldValue) => {
    emit('fetch-data', newValue)
  },
  { deep: true } // Deep watch
)

watch(
  () => props.editTrainingLibrary,
  (newValue) => {
    if (newValue) {
      formData.value = {
        title: newValue.title || '',
        description: newValue.description || '',
        tags: newValue.tags || [],
        script: newValue.script || '',
        topic_id: newValue.topic_id || '',
        tl_video_url: newValue.tl_video_url || null,
        thumbnail_url: newValue.thumbnail_url || null,
      }
    } else {
      resetForm()
    }
  },
  { immediate: true } // Removed deep watcher unless you need it
)

const emit = defineEmits(['update:isDrawerOpen', 'fetch-data'])

const handleDrawerModelValueUpdate = (val) => {
  emit('update:isDrawerOpen', val)
}

const resetForm = () => {
  emit('update:isDrawerOpen', false)
  refVForm.value?.reset()
  uploadedThumbnailFile.value = null
  uploadedVideoFile.value = null
  setTimeout(() => {
    newVideoFile.value = false
    newImageFile.value = false
  }, 1000) // 1-second delay
}

const closeForm = () => {
  emit('update:isDrawerOpen', false)
  uploadedThumbnailFile.value = null
  uploadedVideoFile.value = null
  resetForm()
  setTimeout(() => {
    newVideoFile.value = false
    newImageFile.value = false
  }, 1000) // 1-second delay
}

const replaceVideoFile = () => {
  newVideoFile.value = true
}

const replaceImageFile = () => {
  newImageFile.value = true
}

// Predefined tags
const availableTags = ref([
  'Residential',
  'Commercial',
  'Industrial',
  'Land',
  'Luxury',
  'Apartment',
  'Condominium',
  'Townhouse',
  'Villa',
  'Duplex',
  'Studio',
])

onMounted(() => {
  listForTrainingLibraryTopics()
})
</script>

<template>
  <VNavigationDrawer
    :model-value="props.isDrawerOpen"
    temporary
    location="end"
    width="370"
    class="category-navigation-drawer scrollable-content"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <div class="header d-flex justify-space-between">
      Training Library
      <VBtn size="small" color="error" variant="tonal" @click="closeForm" class="rounded-xl"
        >Cancel</VBtn
      >
    </div>

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm
            ref="refVForm"
            @submit.prevent="
              props.editTrainingLibrary?.id
                ? onFormUpdate(props.editTrainingLibrary?.id)
                : onFormSubmit(activeTab)
            "
          >
            <VRow>
              <VCol cols="12"> </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="formData.title"
                  label="Title"
                  :rules="[requiredValidator]"
                  placeholder="Training title..."
                />
              </VCol>

              <VCol cols="12">
                <span class="text-body-1 d-inline-block">Description</span>
                <ProductDescriptionEditor
                  v-model="formData.description"
                  placeholder="Enter Product Description"
                  class="border mt-1 rounded"
                />
              </VCol>
              <VCol cols="12" v-if="props.editTrainingLibrary?.id">
                <!-- File Preview Section -->
                <p v-if="!newVideoFile"><strong>Video Preview:</strong></p>
                <div class="uploaded-file-preview" style="margin-top: 10px" v-if="!newVideoFile">
                  <video
                    :src="'https://' + editTrainingLibrary.tl_video_url"
                    controls
                    style="
                      max-width: 100%;
                      margin-top: 10px;
                      border: 1px solid #ddd;
                      border-radius: 5px;
                    "
                  ></video>
                  <button
                    class="remove-file-button"
                    @click="replaceVideoFile"
                    style="
                      margin-top: 10px;
                      background-color: #ff4d4f;
                      color: white;
                      padding: 5px 10px;
                      border: none;
                      border-radius: 5px;
                      cursor: pointer;
                    "
                  >
                    Remove File
                  </button>
                </div>
                <div v-else>
                  <div
                    :class="[
                      'upload-box',
                      isDraggingOver ? 'drag-over' : '',
                      theme.name === 'dark' ? 'dark' : 'light',
                    ]"
                    @dragover.prevent="handleDragOver"
                    @dragleave="handleDragLeave"
                    @drop.prevent="handleFileDrop"
                    style="border: 1px solid #4a4949; border-radius: 5px; padding-top: 10px"
                    v-if="!uploadedVideoFile"
                  >
                    <div class="upload-icon">
                      <VIcon>mdi-file-upload-outline</VIcon>
                    </div>
                    <p class="upload-text">Upload Media</p>
                    <p class="upload-instructions">
                      Drop your video here or
                      <label for="fileInputVideo" class="upload-browse">Browse</label>
                      <input
                        id="fileInputVideo"
                        type="file"
                        accept="video/*"
                        :style="'display: none'"
                        @change="handleFileUpload"
                      />
                    </p>
                    <p class="upload-note">Accepted Formats: MP4, AVI, MOV</p>
                  </div>
                </div>
                <!-- File Preview Section -->
                <div
                  v-if="uploadedVideoFile"
                  class="uploaded-file-preview"
                  style="margin-top: 10px"
                >
                  <p><strong>File Details:</strong></p>
                  <p>Name: {{ uploadedVideoFile.name }}</p>
                  <p>Size: {{ (uploadedVideoFile.size / 1024 / 1024).toFixed(2) }} MB</p>
                  <video
                    v-if="isVideoFile(uploadedVideoFile)"
                    :src="fileVideoPreviewUrl"
                    controls
                    style="
                      max-width: 100%;
                      margin-top: 10px;
                      border: 1px solid #ddd;
                      border-radius: 5px;
                    "
                  ></video>
                  <button
                    class="remove-file-button"
                    @click="removeUploadedVideoFile"
                    style="
                      margin-top: 10px;
                      background-color: #ff4d4f;
                      color: white;
                      padding: 5px 10px;
                      border: none;
                      border-radius: 5px;
                      cursor: pointer;
                    "
                  >
                    Remove File
                  </button>
                </div>
              </VCol>
              <VCol cols="12" v-else>
                <!-- 👉 Media Upload Section -->
                <div
                  :class="[
                    'upload-box',
                    isDraggingOver ? 'drag-over' : '',
                    theme.name === 'dark' ? 'dark' : 'light',
                  ]"
                  @dragover.prevent="handleDragOver"
                  @dragleave="handleDragLeave"
                  @drop.prevent="handleFileDrop"
                  style="border: 1px solid #4a4949; border-radius: 5px; padding-top: 10px"
                  v-if="!uploadedVideoFile"
                >
                  <div class="upload-icon">
                    <VIcon>mdi-file-upload-outline</VIcon>
                  </div>
                  <p class="upload-text">Upload Media</p>
                  <p class="upload-instructions">
                    Drop your video here or
                    <label for="fileInputVideo" class="upload-browse">Browse</label>
                    <input
                      id="fileInputVideo"
                      type="file"
                      accept="video/*"
                      :style="'display: none'"
                      @change="handleFileUpload"
                    />
                  </p>
                  <p class="upload-note">Accepted Formats: MP4, AVI, MOV</p>
                </div>

                <!-- File Preview Section -->
                <div
                  v-if="uploadedVideoFile"
                  class="uploaded-file-preview"
                  style="margin-top: 10px"
                >
                  <p><strong>File Details:</strong></p>
                  <p>Name: {{ uploadedVideoFile.name }}</p>
                  <p>Size: {{ (uploadedVideoFile.size / 1024 / 1024).toFixed(2) }} MB</p>
                  <video
                    v-if="isVideoFile(uploadedVideoFile)"
                    :src="fileVideoPreviewUrl"
                    controls
                    style="
                      max-width: 100%;
                      margin-top: 10px;
                      border: 1px solid #ddd;
                      border-radius: 5px;
                    "
                  ></video>
                  <button
                    class="remove-file-button"
                    @click="removeUploadedVideoFile"
                    style="
                      margin-top: 10px;
                      background-color: #ff4d4f;
                      color: white;
                      padding: 5px 10px;
                      border: none;
                      border-radius: 5px;
                      cursor: pointer;
                    "
                  >
                    Remove File
                  </button>
                </div>
              </VCol>

              <VCol cols="12" class="pb-10" v-if="props.editTrainingLibrary?.id">
                <div v-if="!newImageFile" class="uploaded-file-preview" style="margin-top: 10px">
                  <div v-if="editTrainingLibrary.thumbnail_url != null">
                    <p><strong>Thumbnail Preview:</strong></p>
                    <img
                      :src="'https://' + editTrainingLibrary.thumbnail_url"
                      alt="Thumbnail Preview"
                      style="
                        max-width: 100%;
                        height: auto;
                        margin-top: 10px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                      "
                    />
                    <button
                      class="remove-file-button"
                      @click="replaceImageFile"
                      style="
                        margin-top: 10px;
                        background-color: #ff4d4f;
                        color: white;
                        padding: 5px 10px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                      "
                    >
                      Remove Thumbnail
                    </button>
                  </div>
                  <div v-else>
                    <div v-if="!uploadedThumbnailFile">
                      <input
                        id="fileInputThumbnail"
                        type="file"
                        name="file"
                        accept=".jpeg,.png,.jpg,.gif"
                        hidden
                        @change="handleThumbnailUpload"
                      />
                      <VBtn color="primary">
                        <label
                          for="fileInputThumbnail"
                          style="cursor: pointer; display: flex; align-items: center"
                        >
                          <VIcon icon="mdi-cloud-upload" />
                          <span style="margin-left: 4px">Upload Video Thumbnail</span>
                        </label>
                      </VBtn>
                    </div>
                    <div
                      v-if="uploadedThumbnailFile"
                      class="uploaded-file-preview"
                      style="margin-top: 10px"
                    >
                      <p><strong>Thumbnail Preview:</strong></p>
                      <img
                        v-if="isImageFile(uploadedThumbnailFile)"
                        :src="fileThumbnailPreviewUrl"
                        alt="Thumbnail Preview"
                        style="
                          max-width: 100%;
                          height: auto;
                          margin-top: 10px;
                          border: 1px solid #ddd;
                          border-radius: 5px;
                        "
                      />
                      <button
                        class="remove-file-button"
                        @click="removeUploadedThumbnailFile"
                        style="
                          margin-top: 10px;
                          background-color: #ff4d4f;
                          color: white;
                          padding: 5px 10px;
                          border: none;
                          border-radius: 5px;
                          cursor: pointer;
                        "
                      >
                        Remove Thumbnail
                      </button>
                    </div>
                  </div>
                </div>

                <div v-else>
                  <div v-if="!uploadedThumbnailFile">
                    <input
                      id="fileInputThumbnail"
                      type="file"
                      name="file"
                      accept=".jpeg,.png,.jpg,.gif"
                      hidden
                      @change="handleThumbnailUpload"
                    />
                    <VBtn color="primary">
                      <label
                        for="fileInputThumbnail"
                        style="cursor: pointer; display: flex; align-items: center"
                      >
                        <VIcon icon="mdi-cloud-upload" />
                        <span style="margin-left: 4px">Upload Video Thumbnail</span>
                      </label>
                    </VBtn>
                  </div>
                  <div
                    v-if="uploadedThumbnailFile"
                    class="uploaded-file-preview"
                    style="margin-top: 10px"
                  >
                    <p><strong>Thumbnail Preview:</strong></p>
                    <img
                      v-if="isImageFile(uploadedThumbnailFile)"
                      :src="fileThumbnailPreviewUrl"
                      alt="Thumbnail Preview"
                      style="
                        max-width: 100%;
                        height: auto;
                        margin-top: 10px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                      "
                    />
                    <button
                      class="remove-file-button"
                      @click="removeUploadedThumbnailFile"
                      style="
                        margin-top: 10px;
                        background-color: #ff4d4f;
                        color: white;
                        padding: 5px 10px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                      "
                    >
                      Remove Thumbnail
                    </button>
                  </div>
                </div>
              </VCol>
              <VCol cols="12" class="pb-10" v-else>
                <div
                  v-if="uploadedThumbnailFile"
                  class="uploaded-file-preview"
                  style="margin-top: 10px"
                >
                  <p><strong>Thumbnail Preview:</strong></p>
                  <img
                    v-if="isImageFile(uploadedThumbnailFile)"
                    :src="fileThumbnailPreviewUrl"
                    alt="Thumbnail Preview"
                    style="
                      max-width: 100%;
                      height: auto;
                      margin-top: 10px;
                      border: 1px solid #ddd;
                      border-radius: 5px;
                    "
                  />
                  <button
                    class="remove-file-button"
                    @click="removeUploadedThumbnailFile"
                    style="
                      margin-top: 10px;
                      background-color: #ff4d4f;
                      color: white;
                      padding: 5px 10px;
                      border: none;
                      border-radius: 5px;
                      cursor: pointer;
                    "
                  >
                    Remove Thumbnail
                  </button>
                </div>
                <div v-else>
                  <input
                    id="fileInputThumbnail"
                    type="file"
                    name="file"
                    accept=".jpeg,.png,.jpg,.gif"
                    hidden
                    @change="handleThumbnailUpload"
                  />
                  <VBtn color="primary">
                    <label
                      for="fileInputThumbnail"
                      style="cursor: pointer; display: flex; align-items: center"
                    >
                      <VIcon icon="mdi-cloud-upload" />
                      <span style="margin-left: 4px">Upload Video Thumbnail</span>
                    </label>
                  </VBtn>
                </div>
              </VCol>
              <VCol cols="12">
                <span class="text-body-1 d-inline-block">Tags</span>
                <VAutocomplete
                  v-model="formData.tags"
                  placeholder="Type and press enter..."
                  :items="availableTags"
                  multiple
                  chips
                  clearable
                  outlined
                />
              </VCol>
              <VCol cols="12">
                <span class="text-body-1 d-inline-block">Topic</span>
                <VAutocomplete
                  v-model="formData.topic_id"
                  placeholder="Type and press enter..."
                  :items="trainingLibraryTopics"
                  chips
                  item-title="topic"
                  item-value="id"
                  clearable
                  outlined
                />
              </VCol>

              <VCol cols="12">
                <VSwitch v-model="isScriptFile" label="Video Script File Upload" />
              </VCol>

              <VCol cols="12">
                <VSwitch v-model="isScriptFile" label="Video Script File Upload" />
              </VCol>

              <VCol cols="12" class="pb-10" v-if="!isScriptFile">
                <div>Video Script</div>
                <ProductScriptEditor
                  v-model="formData.script"
                  placeholder="Enter Product Script"
                  class="border mt-1 rounded"
                />
              </VCol>
              <VCol v-if="isScriptFile">
                <input
                  id="fileInputScript"
                  type="file"
                  name="file"
                  accept=".jpeg,.png,.jpg,.gif"
                  hidden
                  @change="handleFileScript"
                />
                <VBtn color="primary">
                  <label
                    for="fileInputScript"
                    style="cursor: pointer; display: flex; align-items: center"
                  >
                    <VIcon icon="mdi-cloud-upload" />
                    <span style="margin-left: 4px">Upload Video Script</span>
                  </label>
                </VBtn>
              </VCol>

              <VDivider />

              <VCol cols="12" class="mt-4">
                <div class="d-flex gap-2">
                  <VBtn
                    color="error"
                    variant="tonal"
                    size="small"
                    @click="resetForm"
                    class="rounded-xl"
                  >
                    Cancel
                  </VBtn>
                  <VBtn
                    type="submit"
                    color="primary"
                    size="small"
                    class="rounded-xl"
                    :disabled="isSaving"
                  >
                    <template v-slot:default>
                      <span v-if="!isSaving">{{
                        props.editTrainingLibrary?.id ? 'Update' : 'Save'
                      }}</span>
                      <span v-else>{{
                        props.editTrainingLibrary?.id ? 'Updating...' : 'Saving...'
                      }}</span>
                    </template>
                  </VBtn>
                </div>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>

<style lang="scss" scoped>
.upload-box.drag-over {
  border: 2px dashed #4a90e2;
  background-color: #f0f8ff;
}

.header {
  padding: 10px;
}
.category-navigation-drawer {
  .ProseMirror {
    min-block-size: 9vh !important;

    p {
      margin-block-end: 0;
    }

    p.is-editor-empty:first-child::before {
      block-size: 0;
      color: #adb5bd;
      content: attr(data-placeholder);
      float: inline-start;
      pointer-events: none;
    }

    &-focused {
      outline: none;
    }

    ul,
    ol {
      padding-inline: 1.125rem;
    }
  }

  .is-active {
    border-color: rgba(var(--v-theme-primary), var(--v-border-opacity)) !important;
    background-color: rgba(var(--v-theme-primary), var(--v-activated-opacity));
    color: rgb(var(--v-theme-primary));
  }
}

.upload-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 200px;
  background-color: #f5f6f8;
  border-radius: 10px;
  padding: 20px;
  border: 2px dashed #d3d7de;
  transition: border-color 0.3s ease;
}

.upload-box {
  text-align: center;
  cursor: pointer;
  color: #fff4f4;
}

.upload-container:hover {
  border-color: #1768e4;
}

.upload-icon {
  font-size: 2rem;
  color: #1768e4;
}

.upload-text {
  font-size: 1.2rem;
  font-weight: bold;
  margin: 10px 0;
}

.upload-instructions {
  font-size: 0.9rem;
}

.upload-browse {
  color: #1768e4;
  text-decoration: underline;
  cursor: pointer;
}

.upload-note {
  font-size: 0.8rem;
  color: #8c8c8c;
}
.dark {
  color: #cecece;
}
.light {
  color: #3f3f3f;
}
.scrollable-content {
  scroll-behavior: smooth;
}
</style>
