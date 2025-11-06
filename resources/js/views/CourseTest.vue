<template>
  <div
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50"
    :class="testStarted ? 'no-select' : ''"
    id="assessment-root"
  >
    <!-- Header (Hidden during test) -->
    <div v-if="!testStarted" class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-5xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h1 class="text-xl font-bold text-gray-900">Assessment Portal</h1>
              <p class="text-xs text-gray-500">Secure Testing Environment</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Timer Header (Shown only during test) -->
    <div v-if="testStarted && !status?.passed" class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-5xl mx-auto px-6 py-4">
        <div class="flex items-center justify-center">
          <div class="text-sm text-gray-600">
            <span class="font-medium">Time Remaining:</span>
            <span class="ml-2 font-mono text-lg" :class="timeRemaining < 300 ? 'text-red-600' : 'text-indigo-600'">
              {{ formatTime(timeRemaining) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-8">
      <!-- Tab Switch Warning Modal -->
      <div v-if="showWarning" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md mx-4 animate-bounce-in">
          <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Warning: Violation Detected!</h3>
          <p class="text-center text-gray-600 mb-4">
            You switched tabs or exited fullscreen. Violations: <span class="font-bold text-red-600">{{ tabSwitchCount }}</span>/{{ maxTabSwitches }}
          </p>
          <p class="text-sm text-center text-gray-500 mb-6">
            {{ Math.max(maxTabSwitches - tabSwitchCount, 0) }} warnings remaining before auto-submit
          </p>
          <button @click="closeWarningAndReenterFullscreen" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
            I Understand - Continue Test
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="bg-white rounded-2xl shadow-lg p-12">
        <div class="flex flex-col items-center justify-center space-y-4">
          <div class="w-16 h-16 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
          <p class="text-gray-600 font-medium">Loading assessment...</p>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border-2 border-red-200 rounded-2xl p-6">
        <div class="flex items-start space-x-3">
          <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <h3 class="font-semibold text-red-900 mb-1">Error</h3>
            <p class="text-red-700">{{ error }}</p>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div v-else class="space-y-6">
        <!-- Already Passed Banner -->
        <div v-if="status && status.hasTest && status.passed" class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-8">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="text-xl font-bold text-green-900 mb-2">Congratulations! You've Passed</h3>
              <p class="text-green-700 mb-4">You have successfully completed this assessment.</p>
              <button v-if="status.hasCertificate" @click="downloadCertificate" class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors shadow-md inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Download Certificate</span>
              </button>
              <!-- <p v-else class="text-sm text-green-600 bg-green-100 inline-block px-4 py-2 rounded-lg">
                🎓 Your certificate will be available shortly
              </p> -->
            </div>
          </div>
        </div>

        <!-- Attempts Remaining -->
        <div v-else-if="status && status.hasTest && !testStarted" class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-lg">{{ status.attemptsRemaining }}</span>
              </div>
              <div>
                <p class="font-semibold text-blue-900">Attempts Remaining</p>
                <p class="text-sm text-blue-600">Use them wisely</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Test Info Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white mb-2">{{ test.title }}</h2>
            <p class="text-indigo-100" v-if="test.description">{{ test.description }}</p>
          </div>

          <!-- Instructions -->
          <div
            v-if="!testStarted && !status?.passed && (status?.attemptsRemaining ?? 2) > 0 && !result"
            class="p-8 bg-amber-50 border-b border-amber-200"
          >
            <h3 class="font-bold text-amber-900 mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Important Instructions
            </h3>
            <ul class="space-y-2 text-amber-800">
              <li class="flex items-start">
                <span class="mr-2">⏱️</span>
                <span>You have <strong>{{ testDuration }} minutes</strong> to complete this test</span>
              </li>
              <li class="flex items-start">
                <span class="mr-2">🔒</span>
                <span><strong>Do not switch tabs</strong> or minimize the window during the test</span>
              </li>
              <li class="flex items-start">
                <span class="mr-2">🖥️</span>
                <span><strong>Stay in fullscreen mode</strong> - exiting will count as a violation</span>
              </li>
              <li class="flex items-start">
                <span class="mr-2">⚠️</span>
                <span>After <strong>{{ maxTabSwitches }} violations</strong>, your test will be automatically submitted</span>
              </li>
              <li class="flex items-start">
                <span class="mr-2">💾</span>
                <span>Your answers are <strong>auto-saved</strong> as you progress</span>
              </li>
              <li class="flex items-start">
                <span class="mr-2">✅</span>
                <span>You can submit even if some questions are unanswered</span>
              </li>
            </ul>
            <button
              @click="startTest"
              class="mt-6 w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:from-indigo-700 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
              Start Test Now
            </button>
          </div>

          <!-- Questions Form -->
          <form
            v-if="testStarted && !status?.passed && (status?.attemptsRemaining ?? 2) > 0"
            @submit.prevent="submit(false)"
            class="p-8"
          >
            <!-- Progress Bar -->
            <div class="mb-8">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progress</span>
                <span class="text-sm font-medium text-gray-700">{{ answeredCount }}/{{ questions.length }} answered</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 h-full rounded-full transition-all duration-300" :style="{ width: progressPercent + '%' }"></div>
              </div>
            </div>

            <!-- Page indicator -->
            <div class="flex items-center justify-between mb-4">
              <div class="text-sm text-gray-600">
                Page <span class="font-semibold">{{ currentPage }}</span> of <span class="font-semibold">{{ totalPages }}</span>
              </div>
              <div class="text-sm text-gray-600">
                Showing questions {{ pageStartIndex + 1 }}–{{ Math.min(pageStartIndex + pageSize, questions.length) }}
              </div>
            </div>

            <!-- Questions (5 per page) -->
            <div class="space-y-6">
              <div
                v-for="(q, index) in paginatedQuestions"
                :key="q.id"
                class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-6 hover:border-indigo-300 transition-colors"
              >
                <div class="flex items-start mb-4">
                  <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                    {{ pageStartIndex + index + 1 }}
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900 text-lg">{{ q.question_text }}</p>
                  </div>
                  <div v-if="answers[q.id]" class="flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>

                <div class="space-y-3 ml-11">
                  <label
                    v-for="(label, key) in q.options"
                    :key="key"
                    class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:bg-indigo-50 hover:border-indigo-300"
                    :class="answers[q.id] === key ? 'bg-indigo-50 border-indigo-500' : 'border-gray-200'"
                  >
                    <input
                      type="radio"
                      :name="`q_${q.id}`"
                      :value="key"
                      v-model="answers[q.id]"
                      class="w-5 h-5 text-indigo-600 focus:ring-indigo-500"
                      @change="saveProgress"
                    />
                    <span class="ml-3 text-gray-700 font-medium">{{ label }}</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Pager + Submit -->
            <div class="mt-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 bg-gray-50 rounded-xl">
              <div class="flex items-center gap-3">
                <button
                  type="button"
                  @click="prevPage"
                  :disabled="currentPage === 1"
                  class="px-4 py-3 rounded-lg font-medium border-2 border-gray-300 text-gray-700 hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  ← Previous
                </button>
                <button
                  type="button"
                  @click="nextPage"
                  :disabled="currentPage === totalPages"
                  class="px-4 py-3 rounded-lg font-medium border-2 border-gray-300 text-gray-700 hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Next →
                </button>
              </div>

              <div class="text-sm text-gray-600 order-first md:order-none">
                <p class="font-medium">Review your answers before submitting</p>
                <p class="text-gray-500">You cannot change answers after submission</p>
              </div>

              <button
                v-if="currentPage === totalPages"
                type="submit"
                :disabled="submitting"
                class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-4 rounded-xl font-bold hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transform hover:-translate-y-0.5"
              >
                {{ submitting ? 'Submitting...' : 'Submit Test' }}
              </button>
            </div>
          </form>

          <!-- Result Display -->
          <div v-if="result" class="p-8 border-t-4" :class="result.passed ? 'border-green-500 bg-green-50' : 'border-red-500 bg-yellow-50'">
            <div class="flex items-center justify-between mb-6">
              <div>
                <h3 class="text-2xl font-bold mb-2" :class="result.passed ? 'text-green-900' : 'text-yellow-900'">
                  {{ result.passed ? '🎉 Congratulations!' : '📝 Keep Trying!' }}
                </h3>
                <p class="text-lg" :class="result.passed ? 'text-green-700' : 'text-yellow-700'">
                  Your Score: <span class="font-bold text-2xl">{{ result.score }}%</span>
                </p>
              </div>
              <div class="text-6xl">
                {{ result.passed ? '✅' : '📊' }}
              </div>
            </div>

            <p v-if="(status?.attemptsRemaining ?? 0) > 0" class="text-yellow-700 bg-yellow-100 p-4 rounded-lg">
              Don't worry! You have {{ status.attemptsRemaining }} attempt(s) remaining. Review the material and try again.
               <div class="mt-6 flex gap-3">
              <button
                type="button"
                @click="resetToStart"
                class="px-5 py-3 rounded-lg font-semibold bg-indigo-600 text-white hover:bg-indigo-700"
              >
                Back to Start
              </button>
            </div>
            </p>


          </div>
        </div>

        <!-- Attempt History -->
        <div v-if="status?.attempts?.length && !testStarted" class="bg-white rounded-2xl shadow-lg overflow-hidden">
          <div class="bg-gray-100 px-8 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-900 flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Attempt History
            </h3>
          </div>
          <div class="p-6 space-y-4">
            <div v-for="a in status.attempts" :key="a.id" class="border-2 rounded-xl p-5 hover:shadow-md transition-shadow" :class="a.passed ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-gray-50'">
              <div class="flex justify-between items-center mb-3">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold" :class="a.passed ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700'">
                    #{{ a.attempt_number }}
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">Score: {{ a.score }}%</p>
                    <p class="text-sm" :class="a.passed ? 'text-green-600' : 'text-gray-600'">
                      {{ a.passed ? '✅ Passed' : '❌ Failed' }}
                    </p>    
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm text-gray-600">{{ new Date(a.attempted_at).toLocaleDateString() }}</p>
                  <p class="text-xs text-gray-500">{{ new Date(a.attempted_at).toLocaleTimeString() }}</p>
                </div>
              </div>
              <details class="mt-3">
                <summary class="cursor-pointer text-sm font-medium text-indigo-600 hover:text-indigo-700 select-none">
                  View detailed answers →
                </summary>
                <div class="mt-3 space-y-2 bg-white rounded-lg p-4 border border-gray-200">
                  <div
                    v-for="(ans, index) in a.answers"
                    :key="ans.question_id"
                    class="text-sm flex items-start p-2 rounded"
                    :class="ans.is_correct ? 'bg-green-50' : 'bg-red-50'"
                  >
                    <span :class="ans.is_correct ? 'text-green-600' : 'text-red-600'" class="mr-2">
                      {{ ans.is_correct ? '✓' : '✗' }}
                    </span>
                    <div class="flex-1">
                      <span class="font-medium text-gray-700">Question {{ index + 1 }}:</span>
                      <span class="text-gray-600 ml-2">Selected: {{ ans.selected || 'None' }}</span>
                      <span class="text-gray-400 mx-1">|</span>
                      <span class="text-gray-600">Correct: {{ ans.correct }}</span>
                    </div>
                  </div>
                </div>
              </details>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, nextTick } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const courseId = Number(route.params.courseId || route.params.id);

const loading = ref(true);
const submitting = ref(false);
const error = ref(null);
const test = ref({});
const questions = ref([]);
const answers = ref({});
const result = ref(null);
const status = ref(null);

// Test control
const testStarted = ref(false);
const testDuration = ref(30); // minutes
const timeRemaining = ref(testDuration.value * 60); // seconds
let timerInterval = null;

// Tab monitoring
const tabSwitchCount = ref(0);
const maxTabSwitches = ref(3);
const showWarning = ref(false);
const violationInProgress = ref(false); // Prevent double counting

// Pagination
const pageSize = 5;
const currentPage = ref(1);
const totalPages = computed(() => Math.max(1, Math.ceil(questions.value.length / pageSize)));
const pageStartIndex = computed(() => (currentPage.value - 1) * pageSize);
const paginatedQuestions = computed(() => questions.value.slice(pageStartIndex.value, pageStartIndex.value + pageSize));

// Progress tracking
const answeredCount = computed(() => Object.keys(answers.value).length);
const progressPercent = computed(() => questions.value.length > 0 ? (answeredCount.value / questions.value.length) * 100 : 0);

// Security handlers
let contextMenuHandler = null;
let selectStartHandler = null;

const formatTime = (seconds) => {
  const mins = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
};

const goFullscreen = async () => {
  try {
    const el = document.documentElement;
    if (el.requestFullscreen) await el.requestFullscreen();
    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
    else if (el.msRequestFullscreen) el.msRequestFullscreen();
  } catch (e) {
    // ignore
  }
};

const exitFullscreen = async () => {
  try {
    if (document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {
      if (document.exitFullscreen) await document.exitFullscreen();
      else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
      else if (document.msExitFullscreen) document.msExitFullscreen();
    }
  } catch (e) {
    // ignore
  }
};

const closeWarningAndReenterFullscreen = async () => {
  showWarning.value = false;
  await goFullscreen();
};

const startTest = async () => {
  testStarted.value = true;
  currentPage.value = 1;
  await goFullscreen();

  // disable context menu + selection
  contextMenuHandler = (e) => e.preventDefault();
  selectStartHandler = (e) => e.preventDefault();
  window.addEventListener('contextmenu', contextMenuHandler);
  document.addEventListener('selectstart', selectStartHandler);

  startTimer();
  loadProgress();
  setupTabMonitoring();

  // scroll to top
  await nextTick();
  document.getElementById('assessment-root')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

const startTimer = () => {
  timerInterval = setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value--;
      if (timeRemaining.value === 0) {
        autoSubmit('Time expired');
      }
    }
  }, 1000);
};

const setupTabMonitoring = () => {
  document.addEventListener('visibilitychange', handleVisibilityChange);
  window.addEventListener('blur', handleWindowBlur);
  window.addEventListener('beforeunload', handleBeforeUnload);
  document.addEventListener('fullscreenchange', handleFullscreenChange);
  document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
  document.addEventListener('mozfullscreenchange', handleFullscreenChange);
  document.addEventListener('msfullscreenchange', handleFullscreenChange);
};

const teardownGuards = () => {
  document.removeEventListener('visibilitychange', handleVisibilityChange);
  window.removeEventListener('blur', handleWindowBlur);
  window.removeEventListener('beforeunload', handleBeforeUnload);
  document.removeEventListener('fullscreenchange', handleFullscreenChange);
  document.removeEventListener('webkitfullscreenchange', handleFullscreenChange);
  document.removeEventListener('mozfullscreenchange', handleFullscreenChange);
  document.removeEventListener('msfullscreenchange', handleFullscreenChange);
  if (contextMenuHandler) window.removeEventListener('contextmenu', contextMenuHandler);
  if (selectStartHandler) document.removeEventListener('selectstart', selectStartHandler);
};

const handleVisibilityChange = () => {
  if (document.hidden && testStarted.value && !result.value && !violationInProgress.value) {
    violationInProgress.value = true;
    tabSwitchCount.value++;
    saveProgress();

    if (tabSwitchCount.value >= maxTabSwitches.value) {
      autoSubmit('Maximum tab switches exceeded');
    } else {
      showWarning.value = true;
    }

    // Reset violation flag after a short delay
    setTimeout(() => {
      violationInProgress.value = false;
    }, 1000);
  }
};

const handleWindowBlur = () => {
  // Only count blur if it's not already being counted by visibility change
  if (testStarted.value && !result.value && !violationInProgress.value && !document.hidden) {
    violationInProgress.value = true;
    tabSwitchCount.value++;
    saveProgress();

    if (tabSwitchCount.value >= maxTabSwitches.value) {
      autoSubmit('Maximum tab switches exceeded');
    } else {
      showWarning.value = true;
    }

    // Reset violation flag after a short delay
    setTimeout(() => {
      violationInProgress.value = false;
    }, 1000);
  }
};

const handleFullscreenChange = async () => {
  // Check if user exited fullscreen during test
  const isFullscreen = document.fullscreenElement || document.webkitFullscreenElement ||
                       document.mozFullscreenElement || document.msFullscreenElement;

  if (!isFullscreen && testStarted.value && !result.value && !submitting.value && !violationInProgress.value) {
    violationInProgress.value = true;
    tabSwitchCount.value++;
    saveProgress();

    if (tabSwitchCount.value >= maxTabSwitches.value) {
      autoSubmit('Exited fullscreen mode - Maximum violations exceeded');
    } else {
      showWarning.value = true;
    }

    // Reset violation flag after showing warning
    setTimeout(() => {
      violationInProgress.value = false;
    }, 1000);
  }
};

const handleBeforeUnload = (e) => {
  if (testStarted.value && !result.value && !submitting.value) {
    // Save progress before closing
    saveProgress();

    // Count as violation and submit
    tabSwitchCount.value++;

    // Try to submit synchronously
    const payload = {
      course_id: courseId,
      answers: questions.value.map((q) => ({
        question_id: Number(q.id),
        selected_option: answers.value[q.id] ?? null
      })),
    };

    // Use sendBeacon for reliable submission on page close
    const blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
    navigator.sendBeacon('/test/submit', blob);

    // Show warning message
    e.preventDefault();
    e.returnValue = 'Your test is in progress. Closing this tab will submit your test automatically.';
    return e.returnValue;
  }
};

const saveProgress = () => {
  const progressData = {
    courseId,
    answers: answers.value,
    timeRemaining: timeRemaining.value,
    tabSwitches: tabSwitchCount.value
  };
  localStorage.setItem(`test_progress_${courseId}`, JSON.stringify(progressData));
};

const loadProgress = () => {
  const saved = localStorage.getItem(`test_progress_${courseId}`);
  if (saved) {
    const data = JSON.parse(saved);
    answers.value = data.answers || {};
    timeRemaining.value = typeof data.timeRemaining === 'number' ? data.timeRemaining : testDuration.value * 60;
    tabSwitchCount.value = data.tabSwitches || 0;
  }
};

const clearProgress = () => {
  localStorage.removeItem(`test_progress_${courseId}`);
};

const autoSubmit = async (reason) => {
  if (submitting.value) return;
  alert(`Test auto-submitted: ${reason}`);
  await submit(true);
};

const nextPage = async () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    await nextTick();
    document.getElementById('assessment-root')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
};

const prevPage = async () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    await nextTick();
    document.getElementById('assessment-root')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
};

const loadTest = async () => {
  try {
    loading.value = true;
    const st = await axios.get(`/test/status/${courseId}`);
    status.value = st.data;

    if (!status.value.hasTest) {
      error.value = 'No test available for this course.';
      return;
    }

    if (status.value.passed) {
      return;
    }

    const { data } = await axios.get(`/test/${courseId}`);
    test.value = data.test;
    questions.value = data.questions;
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to load test';
  } finally {
    loading.value = false;
  }
};

const submit = async (isAuto = false) => {
  try {
    submitting.value = true;

    // Build payload including unanswered as null (allow submission even with unanswered questions)
    const payload = {
      course_id: courseId,
      answers: questions.value.map((q) => ({
        question_id: Number(q.id),
        selected_option: answers.value[q.id] ?? null
      })),
    };

    const { data } = await axios.post('/test/submit', payload);
    result.value = data;

    clearProgress();
    clearInterval(timerInterval);
    teardownGuards();
    await exitFullscreen();

    // reload status
    const st = await axios.get(`/test/status/${courseId}`);
    status.value = st.data;

    // After submit, show pre-test interface again (hide questions)
    testStarted.value = false;

    // If passed and certificate is not ready, check once shortly after
    if (result.value.passed && !status.value.hasCertificate) {
      setTimeout(async () => {
        const st2 = await axios.get(`/test/status/${courseId}`);
        status.value = st2.data;
      }, 2000);
    }
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to submit test';
  } finally {
    submitting.value = false;
  }
};

const resetToStart = async () => {
  testStarted.value = false;
  result.value = null;
  answers.value = {};
  currentPage.value = 1;
  timeRemaining.value = testDuration.value * 60;
  tabSwitchCount.value = 0;
  clearProgress();

  // Reload test data and status
  await loadTest();
};

const downloadCertificate = async () => {
  try {
    const token = localStorage.getItem('auth_token');
    const url = `/certificate/download/${courseId}`;
    const response = await axios.get(url, {
      headers: { Authorization: `Bearer ${token}`, Accept: 'application/pdf' },
      responseType: 'blob',
    });
    const blob = new Blob([response.data], { type: 'application/pdf' });
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = `certificate_${courseId}.pdf`;
    link.click();
  } catch (e) {
    alert('Failed to download certificate. Please try again.');
  }
};

onMounted(() => {
  loadTest();
});

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval);
  teardownGuards();
  exitFullscreen();
});
</script>

<style scoped>
@keyframes bounce-in {
  0% { transform: scale(0.8); opacity: 0; }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); opacity: 1; }
}
.animate-bounce-in { animation: bounce-in 0.3s ease-out; }

/* Disable text selection when test is running */
.no-select {
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}
</style>
