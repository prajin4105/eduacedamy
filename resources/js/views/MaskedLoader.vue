<template>
  <div>
    <!-- while we resolve, you could show a spinner -->
    <component v-if="pageComponent" :is="pageComponent" :key="componentKey" v-bind="componentProps" />
    <div v-else class="p-8 text-center text-gray-600">Loading…</div>
  </div>
</template>

<script>
/*
  IMPORTANT:
  The components imported below must match your real views.
  Add or remove imports if your project has different filenames.
*/
import Home from "./Home.vue";
import Courses from "./Courses.vue";
import CourseDetail from "./CourseDetail.vue";
import Pricing from "./Pricing.vue";
import PlanDetail from "./PlanDetail.vue";
import MySubscriptions from "./MySubscriptions.vue";
import EnhancedDashboard from "./EnhancedDashboard.vue";
import StudentDashboard from "./StudentDashboard.vue";
import StudentCourse from "./StudentCourse.vue";
import StudentVideo from "./StudentVideo.vue";
import Certificates from "./Certificates.vue";
import Profile from "./Profile.vue";
import CourseTest from "./CourseTest.vue";
import Chat from "./Chat.vue";

const STORAGE_PREFIX = "masked:";

export default {
  props: ["mask"],

  data() {
    return {
      saved: null,        // saved state object loaded from localStorage
      resolved: false     // whether we loaded state
    };
  },

  computed: {
    // choose component based on saved.page (or fallback to Home)
    pageComponent() {
      const page = this.saved?.page || "home";

      const map = {
        home: Home,
        courses: Courses,
        courseDetail: CourseDetail,
        pricing: Pricing,
        planDetail: PlanDetail,
        subscriptions: MySubscriptions,
        dashboard: EnhancedDashboard,
        studentDashboard: StudentDashboard,
        studentCourse: StudentCourse,
        studentVideo: StudentVideo,
        certificates: Certificates,
        profile: Profile,   
        courseTest: CourseTest,
        chat: Chat,
      };

      return map[page] || Home;
    },

    // props to pass to the chosen component (for example slug)
    componentProps() {
      return this.saved?.params || {};
    },

    // key used to force re-render whenever mask or page changes
    componentKey() {
      return `${this.mask}-${this.saved?.page || "home"}-${this.saved?.params?.slug || ""}`;
    }
  },

  watch: {
    // if URL mask changes, reload mapping
    mask: {
      immediate: true,
      handler() {
        this.resolveMask();
      }
    }
  },

  methods: {
    getStorageKey(mask) {
      return STORAGE_PREFIX + mask;
    },

    resolveMask() {
      // load saved state from localStorage
      const key = this.getStorageKey(this.mask);
      try {
        const raw = localStorage.getItem(key);
        if (raw) {
          this.saved = JSON.parse(raw);
          this.resolved = true;
          return;
        }
      } catch (e) {
        console.warn("MaskedLoader: failed to parse saved mask state", e);
      }

      // No saved mapping found. Try to use history.state (in case navigation used state)
      const histPage = history.state?.page;
      const histParams = history.state?.params;
      if (histPage) {
        this.saved = { page: histPage, params: histParams || {} };
        // persist it for refresh
        try {
          localStorage.setItem(key, JSON.stringify(this.saved));
        } catch (e) {}
        this.resolved = true;
        return;
      }

      // Nothing found: fallback to home (and persist that)
      this.saved = { page: "home", params: {} };
      try {
        localStorage.setItem(key, JSON.stringify(this.saved));
      } catch (e) {}
      this.resolved = true;
    }
  }
};
</script>

<style scoped>
/* optional small style */
</style>
