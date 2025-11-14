<template>
  <div class="min-h-screen bg-gray-50 py-16 px-4">
    <div class="max-w-5xl mx-auto">

      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900">Pricing Plans</h1>
        <p class="text-gray-600 mt-2">Choose the plan that fits you.</p>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-20">
        <div class="animate-spin h-10 w-10 rounded-full border-4 border-gray-300 border-t-indigo-600 mx-auto"></div>
      </div>

      <!-- Plans List -->
<!-- Plans List -->
<div v-else-if="plans.length > 0">

  <!-- If > 3 → Carousel with navigation -->
  <div v-if="plans.length > 3" class="relative">

    <!-- Previous Button -->
    <button
      @click="prevSlide"
      :disabled="currentIndex === 0"
      class="btn-nav absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 z-10"
      aria-label="Previous"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
    </button>

    <!-- Carousel Container -->
    <div class="overflow-hidden">
      <div
        class="flex transition-transform duration-500 ease-out gap-6"
        :style="{ transform: `translateX(-${currentIndex * (100 / cardsToShow)}%)` }"
      >
        <div
          v-for="plan in plans"
          :key="plan.id"
          class="plan-card p-8 relative"
          :class="{ 'popular-card': isPopularPlan(plan) }"
          :style="{ minWidth: `calc(${100 / cardsToShow}% - ${(cardsToShow - 1) * 24 / cardsToShow}px)` }"
        >
          <!-- Popular Badge -->
          <div v-if="isPopularPlan(plan)" class="badge-popular">
            ⭐ Most Popular
          </div>

          <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900">{{ plan.name }}</h3>
            <p class="text-gray-500 text-sm mt-2">{{ plan.description || "Plan details" }}</p>
          </div>

          <div class="text-center my-8">
            <div class="flex items-baseline justify-center">
              <span class="text-5xl font-extrabold text-gray-900">{{ currency(plan.currency) }}{{ Math.floor(plan.price) }}</span>
            </div>
            <p v-if="plan.interval" class="text-gray-500 text-sm mt-1">per {{ plan.interval }}</p>
          </div>

          <ul class="space-y-3 mb-8">
            <li v-for="feature in plan.features || defaultFeatures(plan)" :key="feature" class="flex items-start gap-3">
              <span class="feature-icon">✓</span>
              <span class="text-gray-700 text-sm">{{ feature }}</span>
            </li>
          </ul>

          <div class="space-y-3">
            <button
              @click="subscribe(plan)"
              class="btn-primary w-full"
              :class="{ 'opacity-75 cursor-not-allowed': subscribingPlanId === plan.id }"
              :disabled="subscribingPlanId === plan.id"
            >
              <span v-if="subscribingPlanId === plan.id">Processing…</span>
              <span v-else>Subscribe Now</span>
            </button>

            <button
              @click.prevent="goto('planDetail', { slug: plan.slug })"
              class="btn-outline"
            >
              View Details
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Next Button -->
    <button
      @click="nextSlide"
      :disabled="currentIndex >= plans.length - cardsToShow"
      class="btn-nav absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 z-10"
      aria-label="Next"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </button>

    <!-- Indicators -->
    <div class="flex justify-center gap-2 mt-8">
      <button
        v-for="i in Math.ceil(plans.length / cardsToShow)"
        :key="i"
        @click="goToSlide(i - 1)"
        class="indicator"
        :class="{ active: Math.floor(currentIndex / cardsToShow) === i - 1 }"
        :aria-label="`Go to slide ${i}`"
      />
    </div>
  </div>

  <!-- If <= 3 → Static Grid -->
  <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div
      v-for="plan in plans"
      :key="plan.id"
      class="plan-card p-8 relative"
      :class="{ 'popular-card': isPopularPlan(plan) }"
    >
      <!-- Popular Badge -->
      <div v-if="isPopularPlan(plan)" class="badge-popular">
        ⭐ Most Popular
      </div>

      <div class="text-center">
        <h3 class="text-2xl font-bold text-gray-900">{{ plan.name }}</h3>
        <p class="text-gray-500 text-sm mt-2">{{ plan.description || "Plan details" }}</p>
      </div>

      <div class="text-center my-8">
        <div class="flex items-baseline justify-center">
          <span class="text-5xl font-extrabold text-gray-900">{{ currency(plan.currency) }}{{ Math.floor(plan.price) }}</span>
        </div>
        <p v-if="plan.interval" class="text-gray-500 text-sm mt-1">per {{ plan.interval }}</p>
      </div>

      <ul class="space-y-3 mb-8">
        <li v-for="feature in plan.features || defaultFeatures(plan)" :key="feature" class="flex items-start gap-3">
          <span class="feature-icon">✓</span>
          <span class="text-gray-700 text-sm">{{ feature }}</span>
        </li>
      </ul>

      <div class="space-y-3">
        <button
          @click="subscribe(plan)"
          class="btn-primary w-full"
          :class="{ 'opacity-75 cursor-not-allowed': subscribingPlanId === plan.id }"
          :disabled="subscribingPlanId === plan.id"
        >
          <span v-if="subscribingPlanId === plan.id">Processing…</span>
          <span v-else>Subscribe Now</span>
        </button>

        <button
          @click.prevent="goto('planDetail', { slug: plan.slug })"
          class="btn-outline"
        >
          View Details
        </button>
      </div>
    </div>
  </div>

</div>


      <!-- Empty -->
      <div v-else class="text-center py-20">
        <p class="text-gray-600 text-lg">No Plans Available</p>
      </div>

    </div>
  </div>
</template>



<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { useMaskedNavigation } from '../utils/navigation';

const { goto } = useMaskedNavigation();

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
    else goto('subscriptions');
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
.plan-card {
  @apply bg-white rounded-3xl border border-gray-200 shadow-lg overflow-hidden transition-all hover:shadow-2xl hover:-translate-y-1;
}

.popular-card {
  @apply border-indigo-500 shadow-indigo-100;
}

.badge-popular {
  @apply absolute top-4 right-4 bg-indigo-600 text-white text-xs font-semibold px-4 py-1 rounded-full shadow-lg;
}

.feature-icon {
  @apply h-6 w-6 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold;
}

.btn-primary {
  @apply bg-indigo-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-indigo-700 transition shadow-md;
}

.btn-outline {
  @apply w-full py-3 px-6 border border-indigo-500 text-indigo-600 font-semibold rounded-xl hover:bg-indigo-50 transition;
}

.btn-nav {
  @apply h-12 w-12 flex items-center justify-center bg-white border border-gray-200 rounded-xl shadow hover:bg-gray-100 disabled:opacity-30 disabled:cursor-not-allowed transition;
}

.indicator {
  @apply w-3 h-3 rounded-full bg-gray-300 cursor-pointer transition;
}

.indicator.active {
  @apply bg-indigo-600 w-10 rounded-lg;
}

.empty-box {
  @apply bg-white border border-gray-200 rounded-3xl p-16 shadow-xl max-w-md mx-auto;
}
/* Minimal clean animation */
.animate-spin {
  animation: spin 1s linear infinite;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}

</style>
