<template>
  <div class="min-h-screen bg-gradient-to-br from-white-600 via-white-500 to-indigo-600 py-16 px-4">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">Choose Your Plan</h1>
        <div class="w-32 h-1 bg-white/50 mx-auto mb-4"></div>
        <p class="text-lg text-white/90 max-w-2xl mx-auto">
          Select a subscription that fits your learning journey. Upgrade anytime.
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center text-white py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        <p class="mt-4 text-lg">Loading packages...</p>
      </div>

      <!-- Carousel Container -->
      <div v-else class="relative max-w-6xl mx-auto">
        <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 md:p-12">
          <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Packages</h2>

            <!-- Navigation Buttons -->
            <div class="flex gap-2">
              <button
                @click="prevSlide"
                :disabled="currentIndex === 0"
                class="w-10 h-10 rounded-lg border-2 border-gray-300 flex items-center justify-center hover:border-indigo-500 hover:bg-indigo-50 transition-all disabled:opacity-30 disabled:cursor-not-allowed"
              >
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              <button
                @click="nextSlide"
                :disabled="currentIndex >= plans.length - cardsToShow"
                class="w-10 h-10 rounded-lg border-2 border-gray-300 flex items-center justify-center hover:border-indigo-500 hover:bg-indigo-50 transition-all disabled:opacity-30 disabled:cursor-not-allowed"
              >
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Carousel Slider -->
          <div class="overflow-hidden">
            <div
              class="flex transition-transform duration-500 ease-out"
              :style="{ transform: `translateX(-${currentIndex * slideWidth}%)` }"
            >
              <div
                v-for="(plan, index) in plans"
                :key="plan.id"
                class="flex-shrink-0 px-3"
                :style="{ width: `${slideWidth}%` }"
              >
                <div class="relative rounded-2xl overflow-hidden h-full shadow-lg border border-gray-200 bg-white transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">

                  <!-- Card Header -->
                  <div class="text-black p-6 text-center" :style="{ background: getPlanGradient(plan, index) }">
                    <h3 class="text-2xl font-bold mb-1">{{ plan.name }}</h3>
                    <p class="text-gray-600 text-sm mb-3 truncate w-64">
                      {{ plan.description || 'Access premium courses and advanced features with this plan.' }}
                    </p>
                    <div class="flex items-baseline justify-center mb-4">
                      <span class="text-5xl font-bold">
                        {{ currency(plan.currency) }}{{ Math.floor(plan.price) }}
                      </span>
                      <span class="text-lg ml-2 opacity-90">/{{ plan.interval }}</span>
                    </div>
                  </div>

                  <!-- Card Body -->
                  <div class="bg-white p-6 flex flex-col h-full justify-between">
                    <div>
                      <!-- Features List -->
                      <ul class="space-y-3 mb-6">
                        <li
                          v-for="feature in plan.features || defaultFeatures(plan)"
                          :key="feature"
                          class="flex items-center gap-3 text-sm"
                          :class="getFeatureClass(plan, index)"
                        >
                          <svg class="w-4 h-4 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                              fill-rule="evenodd"
                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                              clip-rule="evenodd"
                            />
                          </svg>
                          <span>{{ feature }}</span>
                        </li>
                      </ul>

                      <!-- Course Count -->
                      <div v-if="plan.course_count" class="mb-4">
                        <span
                          class="inline-block text-xs font-semibold px-3 py-1 rounded-full"
                          :style="{ backgroundColor: getPlanColor(plan, index) + '20', color: getPlanColor(plan, index) }"
                        >
                          {{ plan.course_count }} courses included
                        </span>
                      </div>
                    </div>
                   <div class="mt-4">
                     <router-link
                         :to="`/pricing/${plan.slug}`"
                           class="block w-full py-3 px-6 rounded-full font-semibold border border-indigo-500 text-indigo-600 bg-white hover:bg-indigo-50 transition-all text-center">
                           View Details
                         </router-link>

                         <button
                           @click="subscribe(plan)"
                           class="w-full mt-3 py-3 px-6 rounded-full font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                             Buy Now
                         </button>
                    </div>


                    <!-- Buttons -->
                    <div class="mt-auto flex flex-col gap-3">
                      <!-- View Details Button -->
                      <router-link
                        :to="`/pricing/${plan.slug}`"
                        class="w-full py-3 px-6 rounded-full font-semibold border border-indigo-500 text-indigo-600 bg-white hover:bg-indigo-50 transition-all text-center"
                      >
                        View Details
                      </router-link>

                      <!-- Buy Now Button -->
                   <!-- Buy Now Button -->
                        <button
                          @click="subscribe(plan)"
                          class="w-full py-3 px-6 rounded-full font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg"
                             >
                            <span v-if="subscribingPlanId === plan.id">
                                <svg class="inline-block animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                  <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                  ></circle>
                                  <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                  ></path>
                                </svg>
                                 Subscribing...
                          </span>
                          <span v-else>Buy Now</span>
                        </button>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Dots Indicator -->
          <div class="flex justify-center gap-2 mt-8">
            <button
              v-for="(plan, index) in plans"
              :key="`dot-${plan.id}`"
              @click="goToSlide(index)"
              class="transition-all duration-300 rounded-full"
              :class="index === currentIndex ? 'w-8 h-3 bg-indigo-600' : 'w-3 h-3 bg-gray-300 hover:bg-gray-400'"
            ></button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="!loading && plans.length === 0" class="text-center text-white py-16">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-12 max-w-md mx-auto">
          <svg class="mx-auto h-16 w-16 mb-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
            />
          </svg>
          <h3 class="text-2xl font-semibold mb-2">No Plans Available</h3>
          <p class="text-indigo-100">Check back soon for our pricing options.</p>
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

// Responsive slides
const cardsToShow = computed(() => {
  if (windowWidth.value < 768) return 1;
  if (windowWidth.value < 1024) return 2;
  return 3;
});
const slideWidth = computed(() => 100 / cardsToShow.value);
const highlightedIndex = computed(() => currentIndex.value + Math.floor(cardsToShow.value / 2));

// Neutral color palette
const getPlanGradient = () => 'linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%)';
const getPlanColor = () => '#4F46E5'; // Indigo
const getPlanCardClass = () => 'shadow-md border border-gray-200';
const getFeatureClass = () => 'text-gray-700';

const currency = (code) => {
  const map = { USD: '$', EUR: '€', INR: '₹', GBP: '£' };
  return map[code] || code;
};

const defaultFeatures = (plan) => [
  `Access all ${plan.name} courses`,
  'Progress tracking & certificates',
  'Community discussion access',
  'Cancel anytime'
];

// Load plans from API
const loadPlans = async () => {
  loading.value = true;
  try {
    const { data } = await axios.get('/plans');
    plans.value = data.data || data || [];
  } catch (e) {
    console.error('Failed to load plans', e);
  } finally {
    loading.value = false;
  }
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
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
.animate-spin {
  animation: spin 1s linear infinite;
}
.rounded-2xl {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
button {
  transition: all 0.2s ease;
}
</style>
