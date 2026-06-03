// js/settings/stores/settings.ts
import { create } from 'zustand';
import { persist } from 'zustand/middleware';

import apiFetch from '@wordpress/api-fetch';

import {
    Settings, settingsSchema, ValidationError, zodErrorsToValidationErrors
} from '../lib/schemas';
import { CoverSlice, createCoverSlice } from './slices/cover';

export type SettingsStore = {
  settings: Settings;
  originalSettings: Settings;
  loading: boolean;
  saving: boolean;

  hasUnsavedChanges: boolean;
  loadSettings: () => Promise<void>;
  saveSettings: () => Promise<boolean>;
  resetSettings: () => void;
  updateSetting: <K extends keyof Settings>(
    sliceKey: K,
    updater: (slice: Settings[K]) => Settings[K]
  ) => void;

  validationErrors: ValidationError[];
  validateStore: () => void;
  getValidationError: (path: string) => string | null;

  cover: CoverSlice;
};

export const useSettingsStore = create<SettingsStore>()(
  persist(
    (set, get, store) => {
      const computeValidationErrors = (settings: Settings) => {
        const result = settingsSchema.safeParse(settings);
        return result.success ? [] : zodErrorsToValidationErrors(result.error);
      };

      const computeHasUnsavedChanges = (settings: Settings, original: Settings) =>
        JSON.stringify(settings) !== JSON.stringify(original);

      return {
        settings: settingsSchema.parse({}),
        originalSettings: settingsSchema.parse({}),
        loading: true,
        saving: false,
        hasUnsavedChanges: false,

        validationErrors: [],

        loadSettings: async () => {
          set({ loading: true });
          try {
            const data = await apiFetch({ path: '/camalotewp-editorial-control/v1/settings' });
            const parsed = settingsSchema.parse(data);
            set({
              settings: parsed,
              originalSettings: parsed,
              loading: false,
              validationErrors: computeValidationErrors(parsed),
              hasUnsavedChanges: computeHasUnsavedChanges(parsed, parsed),
            });
          } catch (err) {
            console.error(err);
            set({ loading: false });
          }
        },

        saveSettings: async () => {
          get().validateStore();
          if (get().validationErrors.length > 0) return false;

          set({ saving: true });
          try {
            const saved = await apiFetch({
              path: '/camalotewp-editorial-control/v1/settings',
              method: 'PATCH',
              data: get().settings,
            });
            const parsed = settingsSchema.parse(saved);
            set({
              settings: parsed,
              originalSettings: parsed,
              saving: false,
              validationErrors: computeValidationErrors(parsed),
              hasUnsavedChanges: computeHasUnsavedChanges(parsed, parsed),
            });
            return true;
          } catch (err) {
            console.error(err);
            set({ saving: false });
            return false;
          }
        },

        updateSetting: (sliceKey, updater) => {
          set((state) => {
            const newSlice = updater(state.settings[sliceKey]);
            const newSettings = { ...state.settings, [sliceKey]: newSlice };
            return {
              settings: newSettings,
              validationErrors: computeValidationErrors(newSettings),
              hasUnsavedChanges: computeHasUnsavedChanges(newSettings, state.originalSettings),
            };
          });
        },

        resetSettings: () => {
          const original = get().originalSettings;
          set({
            settings: original,
            validationErrors: computeValidationErrors(original),
            hasUnsavedChanges: computeHasUnsavedChanges(original, original),
          });
        },

        validateStore: () => {
          const errors = computeValidationErrors(get().settings);
          set({ validationErrors: errors });
        },

        getValidationError: (path: string) =>
          get().validationErrors.find((e) => e.field === path)?.message ?? null,

        // slice
        cover: createCoverSlice(set, get, store),
      };
    },
    {
      name: 'camalotewp-editorial-control-form-draft',
      partialize: (state) => ({ settings: state.settings }),
    }
  )
);
