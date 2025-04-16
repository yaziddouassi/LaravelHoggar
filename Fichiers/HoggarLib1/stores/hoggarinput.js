import { defineStore } from 'pinia'
import { useForm } from '@inertiajs/vue3'

export const HoggarInput = defineStore('counter', {
  state: () => ({
    errors: {}, 
    nom: 'hello wesh',
    hogarRecordInput: {},
    hogarDataUrlStorage: '',
    hogarDataDefaultValues: {},
    hogarDataLabels: {},
    hogarDataValues: {},
    hogarDataFields: {},
    hogarDataTypes: {},
    hogarDataOptions: {},
    hogarDataNullables: {},
    hogarNoDatabases: {},
    tempUrls: {},
    tempUrlTabs: {},
    existingFiles: {},
    removedFiles: {},
    wizardForm: {},
    wizardLabel: {},
    wizardCount: 1,
    wizardStop: {},
    wizardCurrent: 1,
    form: useForm({}),
  }),

  actions: {
    setError(err) {
      this.errors = err
    },

    resetError() {
      this.errors = {}
    },

    setFormData(data) {
      this.form = useForm(data)
    },

    resetDatas() {
      Object.entries(this.hogarDataDefaultValues).forEach(([key, value]) => {
        // 🛠 Corrige les valeurs de type checkbox multiple (string JSON → tableau JS)
        if (this.hogarDataTypes[key] === 'CheckBoxMultiple') {
          this.hogarDataValues[key] = this.parseArray(value)
        } else {
          this.hogarDataValues[key] = value
        }

        this.tempUrls[key] = null
        this.tempUrlTabs[key] = []
        this.existingFiles[key] = []
        this.removedFiles[key] = []
      })
    },

    initTempUrls() {
      Object.entries(this.hogarDataDefaultValues).forEach(([key, value]) => {
        this.tempUrls[key] = null
        this.tempUrlTabs[key] = []
        this.existingFiles[key] = []
        this.removedFiles[key] = []
      })
    },

    parseArray(value) {
      
      if (Array.isArray(value)) return value
      if (typeof value === 'string') {
        try {
          const parsed = JSON.parse(value)
          if (Array.isArray(parsed)) return parsed
        } catch {
          return []
        }
      }
      return []
    }
  }
})