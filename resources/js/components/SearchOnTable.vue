<script setup>
import { ref } from 'vue';

const props = defineProps({
  search: String,
  loading: Boolean,
  disabledField: Boolean,
  msgSearch: String,
  placeholder: String,
});

const emit = defineEmits(['update:search', 'onSearch', 'onEnter', 'onClear']);

const handleInput = (value) => {
  emit('update:search', value);
  emit('onSearch', value);
};

const handleEnter = () => {
  emit('onEnter', props.search);
};

const handleClear = () => {
  emit('onClear');
  emit('update:search', '');
};
</script>

<template>
  <div class="d-flex" style="width:400px;">
    <div style="inline-size: 250px">
      <AppTextField 
        v-model="props.search"
        clearable
        @click:clear="handleClear"
        @update:modelValue="handleInput"
        @keydown.enter="handleEnter"
        :placeholder="props.placeholder"
        :disabled="props.disabledField"
      />
    </div>
    <VIcon v-if="props.loading" class="custom-spin" icon="mdi-loading" spin />
    <span v-if="props.msgSearch !== '' && props.search !== ''" class="pa-2 ml-2" style="width:250px;">
      <i>{{ props.msgSearch }}</i>
    </span>
  </div>
</template>

<style scoped>
.custom-spin {
  animation: spin 1s linear infinite;
  margin:8px;
}
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
