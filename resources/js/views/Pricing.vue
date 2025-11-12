<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-50 to-white py-20 px-4">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-16">
        <h1 class="text-6xl md:text-7xl font-extrabold text-indigo-600 mb-6 tracking-tight">
          Choose Your Plan
        </h1>
        <div class="w-24 h-1.5 bg-indigo-600 mx-auto mb-6 rounded-full"></div>
        <p class="text-xl text-gray-700 max-w-2xl mx-auto leading-relaxed">
          Select a subscription that fits your learning journey. Upgrade anytime.
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center text-indigo-600 py-20">
        <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-indigo-200 border-t-indigo-600"></div>
        <p class="mt-6 text-xl font-medium">Loading plans...</p>
      </div>

      <!-- Carousel Container -->
      <div v-else-if="plans.length > 0" class="relative max-w-7xl mx-auto">
        <div class="bg-white rounded-3xl shadow-2xl p-10 md:p-14 border border-gray-100">
          <div class="flex items-center justify-between mb-10">
            <div>
              <h2 class="text-4xl font-bold text-indigo-600">
                Our Packages
              </h2>
              <p class="text-gray-600 mt-2">Select the perfect plan for your journey</p>
            </div>

            <!-- Navigation Buttons - Only show if more than 3 plans -->
            <div v-if="plans.length > 3" class="flex gap-3">
              <button
                @click="prevSlide"
                :disabled="currentIndex === 0"
                class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center hover:bg-indigo-700 transition-all disabled:opacity-30 disabled:cursor-not-allowed shadow-md"
              >
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              <button
                @click="nextSlide"
                :disabled="currentIndex >= plans.length - cardsToShow"
                class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center hover:bg-indigo-700 transition-all disabled:opacity-30 disabled:cursor-not-allowed shadow-md"
              >
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Carousel Slider -->
          <div class="overflow-hidden -mx-4">
            <div
              class="flex transition-transform duration-700 ease-out px-4"
              :style="{ transform: `translateX(-${currentIndex * slideWidth}%)` }"
            >
              <div
                v-for="(plan, index) in plans"
                :key="plan.id"
                class="flex-shrink-0 px-4"
                :style="{ width: `${slideWidth}%` }"
              >
                <div class="relative rounded-3xl overflow-hidden h-full bg-white border-2 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2" :class="isPopularPlan(plan) ? 'border-indigo-600' : 'border-gray-200'">
                  <!-- Popular Badge -->
                  <div v-if="isPopularPlan(plan)" class="absolute top-4 right-4 z-10">
                    <span class="bg-indigo-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                      ⭐ POPULAR
                    </span>
                  </div>

                  <!-- Card Header -->
                  <div class="text-center p-8" :class="isPopularPlan(plan) ? 'bg-indigo-600' : 'bg-indigo-50'">
                    <h3 class="text-3xl font-bold mb-2" :class="isPopularPlan(plan) ? 'text-white' : 'text-indigo-600'">
                      {{ plan.name }}
                    </h3>
                    <p class="text-sm mb-6 max-w-xs mx-auto" :class="isPopularPlan(plan) ? 'text-indigo-100' : 'text-gray-600'">
                      {{ plan.description || 'Access premium courses and advanced features with this plan.' }}
                    </p>
                    <div class="flex items-baseline justify-center mb-2">
                      <span class="text-6xl font-extrabold" :class="isPopularPlan(plan) ? 'text-white' : 'text-indigo-600'">
                        {{ currency(plan.currency) }}{{ Math.floor(plan.price) }}
                      </span>
                    </div>
                    <p v-if="plan.interval" class="text-sm" :class="isPopularPlan(plan) ? 'text-indigo-100' : 'text-gray-600'">
                      per {{ plan.interval }}
                    </p>
                  </div>

                  <!-- Card Body -->
                  <div class="p-8">
                    <!-- Course Count -->
                    <div v-if="plan.course_count" class="mb-6">
                      <span class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-full bg-indigo-50 text-indigo-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                        {{ plan.course_count }} courses included
                      </span>
                    </div>

                    <!-- Features List -->
                    <ul class="space-y-4 mb-8">
                      <li
                        v-for="feature in plan.features || defaultFeatures(plan)"
                        :key="feature"
                        class="flex items-start gap-3"
                      >
                        <svg class="w-6 h-6 flex-shrink-0 mt-0.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700 text-sm leading-relaxed">{{ feature }}</span>
                      </li>
                    </ul>

                    <!-- Buttons -->
                    <div class="space-y-3">
                      <router-link
                        :to="`/pricing/${plan.slug}`"
                        class="block w-full py-4 px-6 rounded-2xl font-semibold border-2 border-indigo-600 text-indigo-600 transition-all hover:bg-indigo-50 text-center"
                      >
                        View Details →
                      </router-link>

                      <button
                        @click="subscribe(plan)"
                        class="w-full py-4 px-6 rounded-2xl font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg hover:shadow-xl transition-all"
                      >
                        <span v-if="subscribingPlanId === plan.id">
                          <svg class="inline-block animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                          Processing...
                        </span>
                        <span v-else>Get Started Now</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Dots Indicator - Only show if more than 3 plans -->
          <div v-if="plans.length > 3" class="flex justify-center gap-2 mt-10">
            <button
              v-for="index in Math.max(1, plans.length - cardsToShow + 1)"
              :key="`dot-${index}`"
              @click="goToSlide(index - 1)"
              class="transition-all duration-300 rounded-full"
              :class="(index - 1) === currentIndex ? 'w-10 h-3 bg-indigo-600' : 'w-3 h-3 bg-gray-300 hover:bg-gray-400'"
            ></button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20">
        <div class="bg-white rounded-3xl p-16 max-w-md mx-auto shadow-xl border border-gray-200">
          <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
          </div>
          <h3 class="text-3xl font-bold mb-3 text-indigo-600">No Plans Available</h3>
          <p class="text-gray-600 text-lg">Check back soon for exciting pricing options!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const plans = ref([]);
const loading = ref(false);
const subscribingPlanId = ref(null);
const currentIndex = ref(0);
const windowWidth = ref(window.innerWidth);
const mostPopularPlanId = ref(null);

// Responsive slides
const cardsToShow = computed(() => {
  if (windowWidth.value < 768) return 1;
  if (windowWidth.value < 1024) return 2;
  return 3;
});

const slideWidth = computed(() => 100 / cardsToShow.value);

const currency = (code) => {
  const map = { USD: '$', EUR: '€', INR: '₹', GBP: '£' };
  return map[code] || code;
};

const defaultFeatures = (plan) => [
  `Full access to ${plan.name} content`,
  'Progress tracking & certificates',
  'Priority support access',
  'Mobile & desktop apps',
  'Cancel anytime, no questions asked'
];

// Load plans from API
const loadPlans = async () => {
  loading.value = true;
  try {
    const { data } = await axios.get('/plans');
    plans.value = data.data || data || [];

    // Get most popular plan from subscription counts
    if (plans.value.length > 0) {
      await getMostPopularPlan();
    }
  } catch (e) {
    console.error('Failed to load plans', e);
  } finally {
    loading.value = false;
  }
};

// Get most popular plan based on subscription count
const getMostPopularPlan = async () => {
  try {
    const { data } = await axios.get('/subscriptions/popular-plan');
    // API should return: { plan_id: 123, subscription_count: 45 }
    mostPopularPlanId.value = data.plan_id;
  } catch (e) {
    console.error('Failed to get popular plan', e);
    // Fallback: if API fails, no plan is marked as popular
    mostPopularPlanId.value = null;
  }
};

// Check if plan is popular
const isPopularPlan = (plan) => {
  return plan.id === mostPopularPlanId.value;
};

// Subscribe handler
const subscribe = async (plan) => {
  try {
    if (subscribingPlanId.value !== null) return;
    subscribingPlanId.value = plan.id;
    const { data } = await axios.post('/subscriptions/subscribe', { plan_id: plan.id });
    if (data.checkout_url) window.location.href = data.checkout_url;
    else window.location.href = '/subscriptions';
  } catch (e) {
    console.error('Subscribe failed', e);
    alert(e?.response?.data?.message || 'Subscription failed');
  } finally {
    subscribingPlanId.value = null;
  }
};

// Carousel controls
const prevSlide = () => {
  if (currentIndex.value > 0) currentIndex.value--;
};

const nextSlide = () => {
  if (currentIndex.value < plans.value.length - cardsToShow.value) currentIndex.value++;
};

const goToSlide = (index) => {
  currentIndex.value = Math.min(index, plans.value.length - cardsToShow.value);
};

const handleResize = () => {
  windowWidth.value = window.innerWidth;
  if (currentIndex.value > plans.value.length - cardsToShow.value) {
    currentIndex.value = Math.max(0, plans.value.length - cardsToShow.value);
  }
};

onMounted(() => {
  loadPlans();
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => window.removeEventListener('resize', handleResize));
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>
