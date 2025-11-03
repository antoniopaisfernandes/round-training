<template>
  <v-dialog scrollable v-model="dataVisible" @keydown.esc="dataVisible = false" max-width="48rem">
    <v-card :loading="isSaving" class="px-5 py-5 vert-card" height="90vh">
      <v-tabs
        fixed-tabs
        v-model="tab"
      >
        <v-tab key="student">Student</v-tab>
        <!-- <v-tab key="programEditions">Program editions</v-tab> -->
        <v-tab key="export" v-if="student.id">Export</v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item key="student">
          <v-card>
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
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-model="dataStudent.citizen_id_validity"
                      label="Validity"
                      prepend-icon="mdi-calendar"
                      readonly
                      v-on="on"
                      class="tw-w-1/4 tw-ml-2"
                      :rules="rules.citizen_id_validity"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="dataStudent.citizen_id_validity"
                    @input="citizenIdValidityDatePickerActive = false"
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
                dark
                class="tw-my-4"
              />
              <v-select
                :items="companies"
                v-model="dataStudent.current_company_id"
                label="Company"
                prepend-icon="mdi-factory"
                required
                :rules="rules.company"
                @input="dataStudent.current_company_id = $event"
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
        </v-tab-item>
        <v-tab-item key="export">
          <export-tab
            :student="dataStudent"
          ></export-tab>
        </v-tab-item>
      </v-tabs-items>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
        <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">Save</v-btn>
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

export default {
  name: 'student-create',

  components: {
    ExportTab,
  },

  model: {
    prop: 'student',
    event: 'input'
  },

  props: {
    student: {
      type: Model,
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
      return this.student.hasOwnProperty('citizen_id')
        && this.student.hasOwnProperty('citizen_id_validity')
    },
  },

  methods: {
    close() {
      this.dataVisible = false
    },
    async save() {
      this.isSaving = true

      try {
        let student = await this.dataStudent.save()
        this.isSaving = false
        this.close()
        this.$emit('input', student)
        this.$emit('saved', student)
      } catch (error) {
        this.isSaving = false
        alert.error(error)
      }
    },
  },

  watch: {
    student: function(value) {
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
