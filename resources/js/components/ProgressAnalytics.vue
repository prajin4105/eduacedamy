<template>
  <div class="progress-analytics">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Total Learning Time -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Learning Time</p>
            <p class="text-2xl font-bold text-gray-900">{{ formatTime(analytics.totalTimeSpent) }}</p>
          </div>
        </div>
      </div>

      <!-- Average Progress -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-green-100 text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Average Progress</p>
            <p class="text-2xl font-bold text-gray-900">{{ analytics.averageProgress }}%</p>
          </div>
        </div>
      </div>

      <!-- Completion Rate -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-purple-100 text-purple-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Completion Rate</p>
            <p class="text-2xl font-bold text-gray-900">{{ analytics.completionRate }}%</p>
          </div>
        </div>
      </div>

      <!-- Learning Streak -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-orange-100 text-orange-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Learning Streak</p>
            <p class="text-2xl font-bold text-gray-900">{{ analytics.learningStreak }} days</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Progress Chart -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Learning Progress Over Time</h3>
      <div class="h-64 flex items-end justify-between space-x-2">
        <div 
          v-for="(day, index) in analytics.weeklyProgress" 
          :key="index"
          class="flex-1 bg-indigo-200 rounded-t"
          :style="`height: ${(day.progress / 100) * 100}%`"
          :title="`${day.date}: ${day.progress}%`"
        ></div>
      </div>
      <div class="mt-4 flex justify-between text-xs text-gray-500">
        <span v-for="(day, index) in analytics.weeklyProgress" :key="index">
          {{ day.date }}
        </span>
      </div>
    </div>

    <!-- Course Performance -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Performance</h3>
      <div class="space-y-4">
        <div 
          v-for="course in analytics.coursePerformance" 
          :key="course.id"
          class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
        >
          <div class="flex-1">
            <h4 class="text-sm font-medium text-gray-900">{{ course.title }}</h4>
            <p class="text-sm text-gray-500">{{ course.instructor }}</p>
          </div>
          <div class="flex items-center space-x-4">
            <div class="text-right">
              <p class="text-sm font-medium text-gray-900">{{ course.progress }}%</p>
              <p class="text-xs text-gray-500">{{ course.timeSpent }}</p>
            </div>
            <div class="w-16 bg-gray-200 rounded-full h-2">
              <div 
                class="bg-indigo-600 h-2 rounded-full transition-all duration-500" 
                :style="`width: ${course.progress}%`"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const analytics = ref({
  totalTimeSpent: 0,
  averageProgress: 0,
  completionRate: 0,
  learningStreak: 0,
  weeklyProgress: [],
  coursePerformance: []
});

const fetchAnalytics = async () => {
  try {
    const response = await axios.get('/api/dashboard/progress');
    
    if (response.data.success) {
      const data = response.data.data;
      
      // Calculate analytics
      analytics.value = {
        totalTimeSpent: data.statistics.total_time_spent_seconds || 0,
        averageProgress: calculateAverageProgress(data),
        completionRate: data.statistics.completion_rate || 0,
        learningStreak: calculateLearningStreak(data),
        weeklyProgress: generateWeeklyProgress(data),
        coursePerformance: generateCoursePerformance(data)
      };
    }
  } catch (error) {
    console.error('Error fetching analytics:', error);
  }
};

const calculateAverageProgress = (data) => {
  const allCourses = [
    ...data.completed_courses,
    ...data.in_progress_courses,
    ...data.not_started_courses
  ];
  
  if (allCourses.length === 0) return 0;
  
  const totalProgress = allCourses.reduce((sum, course) => {
    return sum + (course.enrollment?.progress_percentage || 0);
  }, 0);
  
  return Math.round(totalProgress / allCourses.length);
};

const calculateLearningStreak = (data) => {
  // This would typically come from a more sophisticated tracking system
  // For now, we'll simulate based on recent activity
  const recentActivity = data.statistics.total_time_spent_seconds > 0 ? 1 : 0;
  return recentActivity;
};

const generateWeeklyProgress = (data) => {
  // Generate mock weekly progress data
  const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  return days.map((day, index) => ({
    date: day,
    progress: Math.floor(Math.random() * 100)
  }));
};

const generateCoursePerformance = (data) => {
  const allCourses = [
    ...data.completed_courses,
    ...data.in_progress_courses,
    ...data.not_started_courses
  ];
  
  return allCourses.map(course => ({
    id: course.course.id,
    title: course.course.title,
    instructor: course.course.instructor?.name || 'Unknown',
    progress: course.enrollment?.progress_percentage || 0,
    timeSpent: formatTime(course.progress?.time_spent_seconds || 0)
  }));
};

const formatTime = (seconds) => {
  if (!seconds) return '0s';
  
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  
  if (hours > 0) {
    return `${hours}h ${minutes}m`;
  } else if (minutes > 0) {
    return `${minutes}m`;
  } else {
    return `${seconds}s`;
  }
};

onMounted(() => {
  fetchAnalytics();
});
</script>
