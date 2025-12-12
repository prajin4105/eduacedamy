import { useRouter } from 'vue-router';

const rand = () => Math.random().toString(36).substring(2, 10);
const STORAGE_PREFIX = "masked:";

// Save mask state: mask -> { page, params, timestamp }
const saveMaskState = (mask, state) => {
  try {
    localStorage.setItem(STORAGE_PREFIX + mask, JSON.stringify({ ...state, ts: Date.now() }));
  } catch (e) {
    console.warn("Failed to save mask state", e);
  }
};

// Generic goto function for masked navigation
export const useMaskedNavigation = () => {
  const router = useRouter();

  const goto = (page, params = {}) => {
    const mask = rand();
    // save for refresh
    saveMaskState(mask, { page, params });
    // use named route + params so router recognizes different route instances
    router.push({ name: "MaskedPage", params: { mask }, state: { page, params }});
  };

  return { goto };
};

