<template>
  <v-dialog scrollable v-model="dataVisible" max-width="48rem">
    <v-card class="px-5 py-5 vert-card" height="90vh">
      <v-progress-linear v-if="isSaving" indeterminate color="primary"></v-progress-linear>
      <v-tabs v-model="tab">
        <v-tab value="student">Student</v-tab>
        <!-- <v-tab value="programEditions">Program editions</v-tab> -->
        <v-tab value="export" v-if="modelValue.id">Export</v-tab>
      </v-tabs>

      <v-tabs-window v-model="tab">
        <v-tabs-window-item value="student">
          <v-card variant="flat">
            <v-card-text class="mt-5">
              <v-text-field
                autofocus
                v-model="dataStudent.name"
                label="Name"
                prepend-icon="mdi-account-edit-outline"
                required
                :rules="rules.name"
              ></v-text-field>
              <v-text-field
                v-model="dataStudent.address"
                label="Address"
                prepend-icon="mdi-map-marker"
                required
                :rules="rules.address"
              ></v-text-field>
              <div class="tw-flex">
                <v-text-field
                  v-model="dataStudent.postal_code"
                  label="Postal C."
                  prepend-icon="mdi-city"
                  required
                  class="tw-w-1/4"
                  :rules="rules.postal_code"
                ></v-text-field>
                <v-text-field
                  v-model="dataStudent.city"
                  label="City"
                  prepend-icon="mdi-city"
                  required
                  class="tw-w-3/4 tw-ml-2"
                  :rules="rules.city"
                ></v-text-field>
              </div>
              <div v-if="rgpd" class="tw-flex">
                <v-text-field
                  v-model="dataStudent.citizen_id"
                  label="Citizen card"
                  prepend-icon="mdi-card-account-details-outline"
                  required
                  class="tw-w-3/4"
                  :rules="rules.citizen_id"
                ></v-text-field>
                <v-menu
                  v-model="citizenIdValidityDatePickerActive"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  min-width="auto"
                >
                  <template v-slot:activator="{ props }">
                    <v-text-field
                      v-model="dataStudent.citizen_id_validity"
                      label="Validity"
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="props"
                      class="tw-w-1/4 tw-ml-2"
                      :rules="rules.citizen_id_validity"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="citizenIdValidityDate"
                    @update:model-value="onCitizenIdValiditySelected"
                  ></v-date-picker>
                </v-menu>
              </div>
              <v-text-field
                v-model="dataStudent.email"
                label="E-mail"
                type="email"
                prepend-icon="mdi-email-outline"
                required
                :rules="rules.email"
              ></v-text-field>
              <div v-if="rgpd" class="tw-flex">
                <v-text-field
                  v-model="dataStudent.birth_place"
                  label="Birth place"
                  prepend-icon="mdi-map-marker"
                  required
                  :rules="rules.birth_place"
                ></v-text-field>
                <v-text-field
                  v-model="dataStudent.nationality"
                  label="Birth country"
                  prepend-icon="mdi-map-marker"
                  required
                  class="tw-ml-2"
                  :rules="rules.nationality"
                ></v-text-field>
              </div>
              <v-divider
                class="tw-my-4"
              />
              <v-select
                :items="companies"
                v-model="dataStudent.current_company_id"
                label="Company"
                prepend-icon="mdi-factory"
                required
                :rules="rules.company"
                @update:model-value="dataStudent.current_company_id = $event"
                item-title="text"
                item-value="value"
              ></v-select>
              <v-text-field
                v-model="dataStudent.current_job_title"
                label="Job title"
                prepend-icon="mdi-barcode-scan"
                required
                :rules="rules.current_job_title"
              ></v-text-field>
            </v-card-text>
          </v-card>
        </v-tabs-window-item>
        <v-tabs-window-item value="export">
          <export-tab
            :student="dataStudent"
          ></export-tab>
        </v-tabs-window-item>
      </v-tabs-window>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue-darken-1" variant="text" @click="close">Cancel</v-btn>
        <v-btn color="blue-darken-1" variant="text" :disabled="isSaveDisabled" @click="save">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import ExportTab from './export.vue'
import Student from '../../models/Student'
import Company from '../../models/Company'
import Model from '../../models/Model'
import alert from '../../plugins/toast'
import map from 'lodash-es/map'
import { format } from 'date-fns'

export default {
  name: 'student-create',

  components: {
    ExportTab,
  },

  emits: ['update:modelValue', 'close', 'saved'],

  props: {
    modelValue: {
      type: Object,
      default: function() {
        return new Student()
      }
    },
    visible: {
      type: Boolean
    },
  },

  data: () => ({
    dataStudent: new Student(),
    isSaving: false,
    citizenIdValidityDatePickerActive: false,
    citizenIdValidityDate: null,
    companies: [],
    tab: null,
  }),

  computed: {
    dataVisible: {
      get () {
        return this.visible
      },
      set (value) {
        if (! value) {
          this.$emit('close')
          this.dataStudent = new Student()
        }
      }
    },
    isSaveDisabled() {
      return ! this.dataStudent.name
        || ! this.dataStudent.current_company_id
        || this.isSaving
    },
    rules() {
      return {
        name: [
          v => !!v || 'The field should have a value.'
        ],
        vat_number: [
          v => !!v || 'The field should have a value.'
        ]
      }
    },
    rgpd() {
      return Object.prototype.hasOwnProperty.call(this.modelValue, 'citizen_id')
        && Object.prototype.hasOwnProperty.call(this.modelValue, 'citizen_id_validity')
    },
  },

  methods: {
    onCitizenIdValiditySelected(date) {
      this.dataStudent.citizen_id_validity = date ? format(new Date(date), 'yyyy-MM-dd') : null
      this.citizenIdValidityDatePickerActive = false
    },
    close() {
      this.dataVisible = false
    },
    async save() {
      this.isSaving = true

      try {
        let student = await this.dataStudent.save()
        this.isSaving = false
        this.close()
        this.$emit('update:modelValue', student)
        this.$emit('saved', student)
      } catch (error) {
        this.isSaving = false
        alert.error(error)
      }
    },
  },

  watch: {
    modelValue: function(value) {
      this.dataStudent = value
    },
    visible: function(value) {
      this.dataVisible = value
    },
  },

  mounted: async function () {
    try {
      let data = await Company.get()

      this.companies = map(data, (v) => {
        return {
          'text': v.name,
          'value': v.id,
        }
      })
    } catch (error) {
      alert.error(error)
    }
  },

}
</script>
