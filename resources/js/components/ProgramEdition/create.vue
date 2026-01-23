<template>
  <v-dialog persistent v-model="dataVisible" max-width="48rem">
    <v-card class="px-5 py-5 vert-card" height="90vh">
      <v-progress-linear v-if="isSaving" indeterminate color="primary"></v-progress-linear>
      <v-tabs v-model="tab">
        <v-tab value="programEdition">Program edition</v-tab>
        <v-tab value="students">Students</v-tab>
        <v-tab value="export" v-if="dataProgramEdition.id">Export</v-tab>
      </v-tabs>

      <v-tabs-window v-model="tab">
        <v-tabs-window-item value="programEdition">
          <v-card variant="flat">
            <v-card-text>
              <div class="tw-flex">
                <div class="tw-flex tw-flex-row tw-justify-center tw-items-center tw-w-2/3">
                  <v-select
                    :items="programs"
                    v-model="dataProgramEdition.program_id"
                    label="Program name"
                    required
                    :rules="rules.program_id"
                    @update:model-value="dataProgramEdition.program_id = $event"
                    class="tw-w-8/12"
                    item-title="text"
                    item-value="value"
                  ></v-select>
                  <v-btn
                    icon
                    size="small"
                    color="primary"
                    @click="addProgramDialogVisible = true"
                    class="tw--mt-2"
                  >
                    <v-icon>mdi-plus</v-icon>
                  </v-btn>
                  <add-program-dialog
                    :existing-programs="programs"
                    :visible="addProgramDialogVisible"
                    @close="addProgramDialogVisible = false"
                    @update:model-value="selectProgramId($event)"
                  ></add-program-dialog>
                </div>
                <v-text-field
                  v-model="dataProgramEdition.name"
                  label="Edition name"
                  required
                  :rules="rules.name"
                  class="ml-4 tw-w-1/3"
                ></v-text-field>
              </div>
              <div class="tw-flex tw-flex-row tw-justify-center tw-items-center">
                <v-menu
                  v-model="startsAtActive"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  min-width="auto"
                >
                  <template v-slot:activator="{ props }">
                    <v-text-field
                      v-model="dataProgramEdition.starts_at"
                      label="Starts at"
                      prepend-inner-icon="mdi-calendar"
                      readonly
                      v-bind="props"
                      :rules="rules.starts_at"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="startsAtDate"
                    @update:model-value="onStartsAtSelected"
                  ></v-date-picker>
                </v-menu>
                <v-menu
                  v-model="endsAtActive"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  min-width="auto"
                >
                  <template v-slot:activator="{ props }">
                    <v-text-field
                      v-model="dataProgramEdition.ends_at"
                      label="Ends at"
                      prepend-inner-icon="mdi-calendar"
                      readonly
                      v-bind="props"
                      class="tw-ml-2"
                      :rules="rules.ends_at"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="endsAtDate"
                    @update:model-value="onEndsAtSelected"
                  ></v-date-picker>
                </v-menu>
                <v-select
                  :items="companies"
                  v-model="dataProgramEdition.company_id"
                  label="Company"
                  required
                  :rules="rules.company_id"
                  @update:model-value="dataProgramEdition.company_id = $event"
                  class="tw-ml-2 tw-w-6/12"
                  item-title="text"
                  item-value="value"
                ></v-select>
                <v-text-field
                  v-model="dataProgramEdition.cost"
                  label="Cost"
                  required
                  :rules="rules.cost"
                  class="tw-ml-2 tw-w-1/6 money"
                  prefix="$"
                ></v-text-field>
              </div>
              <div class="tw-flex tw-flex-row tw-justify-center tw-items-center">
                <v-text-field
                  v-model="dataProgramEdition.supplier"
                  label="Supplier"
                  required
                  :rules="rules.supplier"
                ></v-text-field>
                <v-text-field
                  v-model="dataProgramEdition.supplier_certifications"
                  label="Certifications"
                  required
                  :rules="rules.supplier_certifications"
                  class="ml-2"
                ></v-text-field>
                <v-text-field
                  v-model="dataProgramEdition.teacher_name"
                  label="Teacher name"
                  required
                  :rules="rules.teacher_name"
                  class="ml-2"
                ></v-text-field>
              </div>

              <div class="tw-flex tw-flex-row tw-justify-center tw-items-center">
                <v-menu
                  v-model="evaluationNotificationDateActive"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  min-width="auto"
                >
                  <template v-slot:activator="{ props }">
                    <v-text-field
                      v-model="dataProgramEdition.evaluation_notification_date"
                      label="Notify accessment to"
                      prepend-inner-icon="mdi-calendar"
                      readonly
                      v-bind="props"
                      :rules="rules.evaluation_notification_date"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="evaluationNotificationDate"
                    @update:model-value="onEvaluationDateSelected"
                  ></v-date-picker>
                </v-menu>
                <v-text-field
                  v-model="dataProgramEdition.goals"
                  label="Training goals"
                  required
                  :rules="rules.goals"
                  class="tw-ml-2 tw-w-3/4"
                ></v-text-field>
              </div>

              <div class="tw-mt-2">
                <span class="text-subtitle-1">Schedules</span>
                <v-btn
                  icon
                  size="small"
                  color="primary"
                  @click="addSchedule"
                  class="tw-ml-2 tw--mt-2"
                >
                  <v-icon>mdi-plus</v-icon>
                </v-btn>
              </div>

              <div v-bind:key="i" v-for="(schedule, i) in dataProgramEdition.schedules" class="tw-flex">
                <div class="tw-w-1/4">
                  <v-datetime-picker label="Start" v-model="schedule.starts_at" timeFormat="HH:mm"></v-datetime-picker>
                </div>
                <div class="tw-w-1/4 tw-ml-2">
                  <v-datetime-picker label="End" v-model="schedule.ends_at" timeFormat="HH:mm"></v-datetime-picker>
                </div>
                <div class="tw-w-1/4 tw-ml-2">
                  <v-datetime-picker label="Break start" v-model="schedule.interval_start" timeFormat="HH:mm"></v-datetime-picker>
                </div>
                <div class="tw-w-20/100 tw-ml-2">
                  <v-text-field label="Break minutes" v-model="schedule.interval_minutes"></v-text-field>
                </div>
                <div class="tw-w-5/100 tw-ml-2 tw-flex tw-items-center">
                  <v-btn
                    icon
                    size="small"
                    color="error"
                    @click="deleteSchedule(i)"
                    class="tw-ml-2 tw--mt-2"
                  >
                    <v-icon>mdi-minus</v-icon>
                  </v-btn>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-tabs-window-item>
        <v-tabs-window-item value="students">
          <students-tab
            :students="students"
            :program-edition="dataProgramEdition"
            @add="students.push($event)"
            @delete="students.splice($event, 1)"
          ></students-tab>
        </v-tabs-window-item>
        <v-tabs-window-item value="export" v-if="dataProgramEdition.id">
          <export-tab
            :program-edition="dataProgramEdition"
          ></export-tab>
        </v-tabs-window-item>
      </v-tabs-window>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="managerName"
          label="Created by"
          :disabled="true"
        ></v-text-field>
        <v-btn class="ml-2" color="blue-darken-1" variant="text" @click="close">Cancel</v-btn>
        <v-btn class="ml-2" color="blue-darken-1" variant="text" :disabled="isSaveDisabled" @click="save">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import AddProgramDialog from '../Program/create.vue'
import StudentsTab from './students.vue'
import ExportTab from './export.vue'
import Program from '../../models/Program'
import Enrollment from '../../models/Enrollment'
import Company from '../../models/Company'
import ProgramEdition from '../../models/ProgramEdition'
import ProgramEditionSchedule from '../../models/ProgramEditionSchedule'
import Model from '../../models/Model'
import alert from '../../plugins/toast'
import map from 'lodash-es/map'
import { addMonths, parseISO, format } from 'date-fns'

export default {
  name: 'program-edition-create',

  components: {
    AddProgramDialog,
    StudentsTab,
    ExportTab,
  },

  emits: ['update:modelValue', 'close', 'saved'],

  props: {
    modelValue: {
      type: Object,
      default: function() {
        return new ProgramEdition()
      }
    },
    visible: {
      type: Boolean
    },
  },

  data: () => ({
    dataProgramEdition: new ProgramEdition(),
    isSaving: false,

    companies: [],
    programs: [],
    students: [],
    startsAtActive: false,
    startsAtDate: null,
    endsAtActive: false,
    endsAtDate: null,
    addProgramDialogVisible: false,
    evaluationNotificationDateActive: false,
    evaluationNotificationDate: null,

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
          this.tab = null
          this.dataProgramEdition = new ProgramEdition()
        }
      }
    },
    isSaveDisabled() {
      return ! this.dataProgramEdition.name
        || this.isSaving
    },
    rules() {
      return {
        name: [
          v => !!v || 'The field should have a value.'
        ],
        program_id: [
          v => !!v || 'The field should have a value.'
        ]
      }
    },
    managerName() {
      return this.dataProgramEdition.manager?.name
    },
  },

  methods: {
    onStartsAtSelected(date) {
      this.dataProgramEdition.starts_at = date ? format(new Date(date), 'yyyy-MM-dd') : null
      this.startsAtActive = false
    },
    onEndsAtSelected(date) {
      this.dataProgramEdition.ends_at = date ? format(new Date(date), 'yyyy-MM-dd') : null
      this.endsAtActive = false
      this.updateEvaluationNotificationDate()
    },
    onEvaluationDateSelected(date) {
      this.dataProgramEdition.evaluation_notification_date = date ? format(new Date(date), 'yyyy-MM-dd') : null
      this.evaluationNotificationDateActive = false
    },
    close() {
      this.dataVisible = false
    },
    async save() {
      this.isSaving = true

      try {
        this.dataProgramEdition.enrollments = this.students.map((student) => {
          return new Enrollment({
            'id': student.pivot.id,
            'student_id': student.id,
            'program_edition_id': this.dataProgramEdition.id,
            'company_id': student.current_company_id,
            'hours_attended': student.pivot.hours_attended
          })
        }) || []
        let programEdition = await this.dataProgramEdition.save()
        this.dataProgramEdition = new ProgramEdition()
        this.isSaving = false
        this.close()
        this.$emit('update:modelValue', programEdition)
        this.$emit('saved', programEdition)
      } catch (error) {
        this.isSaving = false
        alert.error(error)
      }
    },

    // Program names
    selectProgramId(program) {
      if (! program) {
        return
      }

      this.programs.push({
        text: program.name,
        value: program.id
      })
      this.dataProgramEdition.program_id = program.id
    },

    // Schedules
    addSchedule() {
      this.dataProgramEdition.schedules.push(new ProgramEditionSchedule)
    },
    deleteSchedule(index) {
      this.dataProgramEdition.schedules.splice(index, 1)
    },

    updateEvaluationNotificationDate() {
      if (! this.dataProgramEdition.evaluation_notification_date
        || ! this.dataProgramEdition.id
      ) {
        if (this.dataProgramEdition.ends_at) {
          this.dataProgramEdition.evaluation_notification_date = format(
            addMonths(parseISO(this.dataProgramEdition.ends_at), 6),
            'yyyy-LL-dd'
          );
        }
      }
    },

    async getStudents() {
      this.students = []
      if (! this.modelValue.id) {
        return;
      }

      this.isSaving = true
      try {
        let data = await this.modelValue.students().get()
        this.students = data || []
      } catch (error) {
        alert.error(error)
      } finally {
        this.isSaving = false
      }
    },

    // OnMount
    async getPrograms() {
      try {
        let data = await Program.get()

        this.programs = map(data, (v) => {
          return {
            'text': v.name,
            'value': v.id,
          }
        })
      } catch (error) {
        alert.error(error)
      }
    },
    async getCompanies() {
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

  },

  watch: {
    modelValue: function(value) {
      this.dataProgramEdition = value
      this.getStudents()
    },
    visible: function(value) {
      this.dataVisible = value
    },
  },

  mounted: async function () {
    this.getPrograms()
    this.getCompanies()
  },
}
</script>

<style>
  .money input {
    text-align: end;
  }

  .v-card {
    box-shadow: none;
  }

  .vert-card {
    display: flex !important;
    flex-direction: column;
  }

  .v-tabs {
    flex: none !important;
  }

  .v-tabs-window {
    flex: 1;
    position: relative;
    overflow-y: auto;
  }
</style>
