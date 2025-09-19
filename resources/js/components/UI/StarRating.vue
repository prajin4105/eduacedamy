<template>
  <div class="flex items-center">
    <div class="rating">
      <input 
        v-for="i in 5" 
        :key="i" 
        type="radio" 
        :name="name" 
        :class="[sizeClass, 'mask mask-star-2 bg-orange-400', { 'opacity-30': i > modelValue }]"
        :checked="i === modelValue"
        @click="updateRating(i)"
        :disabled="disabled"
      />
    </div>
    <span v-if="showText && modelValue > 0" class="ml-2 text-sm text-gray-600">
      {{ modelValue }} {{ modelValue === 1 ? 'star' : 'stars' }}
    </span>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: {
    type: Number,
    default: 0
  },
  name: {
    type: String,
    default: 'rating'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  showText: {
    type: Boolean,
    default: true
  },
  size: {
    type: String,
    default: 'md', // sm, md, lg
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  }
});

const emit = defineEmits(['update:modelValue']);

const sizeClass = computed(() => {
  const sizes = {
    sm: 'w-4 h-4',
    md: 'w-5 h-5',
    lg: 'w-6 h-6'
  };
  return sizes[props.size];
});

const updateRating = (value) => {
  if (props.disabled) return;
  emit('update:modelValue', value);
};
</script>
