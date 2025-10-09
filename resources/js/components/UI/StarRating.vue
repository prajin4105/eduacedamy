<template>
  <div class="flex items-center space-x-1">
    <button
      v-for="star in 5"
      :key="star"
      type="button"
      @click="updateRating(star)"
      @mouseover="hoverRating = star"
      @mouseleave="hoverRating = 0"
      class="focus:outline-none"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
        :class="[
          (hoverRating >= star || modelValue >= star)
            ? 'text-yellow-400'
            : 'text-gray-300',
          'w-8 h-8 transition-colors duration-150'
        ]"
      >
        <path
          fill-rule="evenodd"
          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.463a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118L10 14.347l-3.95 2.727c-.785.57-1.84-.196-1.54-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.046 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.285-3.974z"
          clip-rule="evenodd"
        />
      </svg>
    </button>

    <span v-if="showText" class="ml-2 text-sm text-gray-600">
      {{ ratingText }}
    </span>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  modelValue: { type: Number, default: 0 },
  showText: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue'])

const hoverRating = ref(0)

const updateRating = (star) => {
  emit('update:modelValue', star)
}

const ratingText = computed(() => {
  if (!props.modelValue) return 'No rating'
  const labels = ['Very Bad', 'Bad', 'Okay', 'Good', 'Excellent']
  return labels[props.modelValue - 1] || ''
})
</script>
