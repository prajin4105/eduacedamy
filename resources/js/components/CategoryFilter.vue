<template>
  <div class="category-filter">
    <h3 class="text-lg font-semibold mb-4">Categories</h3>
    <div class="space-y-2">
      <router-link
        v-for="category in categories"
        :key="category.id"
        :to="{ name: 'category-courses', params: { slug: category.slug } }"
        class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200"
        :class="{
          'bg-blue-50 text-blue-700 font-medium': isActive(category.slug),
          'text-gray-700 hover:bg-gray-50': !isActive(category.slug)
        }"
      >
        <span class="mr-2">{{ category.name }}</span>
        <span
          v-if="category.courses_count"
          class="ml-auto text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full"
          :class="{ 'bg-blue-100 text-blue-700': isActive(category.slug) }"
        >
          {{ category.courses_count }}
        </span>
      </router-link>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
  name: 'CategoryFilter',

  setup() {
    const route = useRoute();
    const categories = ref([]);
    const loading = ref(true);
    const error = ref(null);

    const isActive = (slug) => {
      return route.params.slug === slug;
    };

    const fetchCategories = async () => {
      try {
        loading.value = true;
        const response = await axios.get('/categories');
        categories.value = response.data.data;
      } catch (err) {
        console.error('Error fetching categories:', err);
        error.value = 'Failed to load categories. Please try again later.';
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchCategories();
    });

    return {
      categories,
      loading,
      error,
      isActive
    };
  }
};
</script>

<style scoped>
.category-filter {
  @apply p-4 bg-white rounded-lg shadow-sm border border-gray-100;
}

.router-link-active {
  @apply bg-blue-50 text-blue-700 font-medium;
}
</style>
