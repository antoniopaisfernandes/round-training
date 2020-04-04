<template>
  <v-dialog persistent scrollable v-model="dataVisible" @keydown.esc="dataVisible = false" max-width="48rem">
    <v-card :loading="isSaving" class="px-5 py-5" height="90vh">
      <v-card-title>
        <span class="headline">Curso</span>
      </v-card-title>
      <v-card-text class="mt-5">
        <div class="tw-flex">
          <div class="tw-flex tw-flex-row tw-justify-center tw-items-center tw-w-2/3">

            <v-select
              :items="programs"
              v-model="dataProgramEdition.program_id"
              label="Nome do curso"
              required
              :rules="rules.program_id"
              @input="dataProgramEdition.program_id = $event"
              class="tw-w-8/12"
            ></v-select>

            <v-btn
              fab
              dark
              x-small
              color="primary"
              @click="addProgramDialogVisible = true"
              class="tw--mt-2"
            >
              <v-icon dark>mdi-plus</v-icon>
            </v-btn>
            <add-program-dialog
              :existing-programs="programs"
              :visible="addProgramDialogVisible"
              @close="addProgramDialogVisible = false"
              @input="selectProgramId($event)"
            ></add-program-dialog>

          </div>
          <v-text-field
            v-model="dataProgramEdition.name"
            label="Nome da edição"
            required
            :rules="rules.name"
            class="ml-4 tw-w-1/3"
          ></v-text-field>
        </div>
        <div class="tw-flex tw-flex-row tw-justify-center tw-items-center">
          <v-menu
            v-model="startsAtActive"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="dataProgramEdition.starts_at"
                label="Início a"
                prepend-inner-icon="mdi-calendar"
                readonly
                v-on="on"
                :rules="rules.starts_at"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="dataProgramEdition.starts_at"
              @input="startsAtActive = false"
            ></v-date-picker>
          </v-menu>
          <v-menu
            v-model="endsAtActive"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="dataProgramEdition.ends_at"
                label="Fim a"
                prepend-inner-icon="mdi-calendar"
                readonly
                v-on="on"
                class="tw-ml-2"
                :rules="rules.ends_at"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="dataProgramEdition.ends_at"
              @input="endsAtActive = false"
            ></v-date-picker>
          </v-menu>
          <v-text-field
            v-model="managerName"
            label="Criado por"
            :disabled="true"
            class="ml-2"
          ></v-text-field>
        </div>
        <div class="tw-flex tw-flex-row tw-justify-center tw-items-center">
          <v-text-field
            v-model="dataProgramEdition.supplier"
            label="Fornecedor"
            required
            :rules="rules.supplier"
          ></v-text-field>
          <v-text-field
            v-model="dataProgramEdition.teacher_name"
            label="Formador"
            required
            :rules="rules.teacher_name"
            class="ml-2"
          ></v-text-field>
          <v-text-field
            v-model="dataProgramEdition.cost"
            label="Valor"
            required
            :rules="rules.cost"
            class="ml-2 tw-w-4 money"
            prefix="€"
          ></v-text-field>
        </div>

        <div class="mt-2">
          <span class="subtitle-1">Agendamentos</span>
          <v-btn
            fab
            dark
            x-small
            color="primary"
            @click="addSchedule"
            class="tw-ml-2 tw--mt-2"
          >
            <v-icon dark>mdi-plus</v-icon>
          </v-btn>
        </div>

        <div v-bind:key="i" v-for="(schedule, i) in dataProgramEdition.schedules" class="tw-flex">
          <div class="tw-w-1/4">
            <v-datetime-picker label="Início" v-model="schedule.starts_at" timeFormat="HH:mm"></v-datetime-picker>
          </div>
          <div class="tw-w-1/4 tw-ml-2">
            <v-datetime-picker label="Fim" v-model="schedule.ends_at" timeFormat="HH:mm"></v-datetime-picker>
          </div>
          <div class="tw-w-1/4 tw-ml-2">
            <v-datetime-picker label="Início intervalo" v-model="schedule.interval_start" timeFormat="HH:mm"></v-datetime-picker>
          </div>
          <div class="tw-w-20/100 tw-ml-2">
            <v-text-field label="Duranção intervalo" v-model="schedule.interval_minutes"></v-text-field>
          </div>
          <div class="tw-w-5/100 tw-ml-2 tw-flex tw-items-center">
            <v-btn
              fab
              dark
              x-small
              color="error"
              @click="deleteSchedule(i)"
              class="tw-ml-2 tw--mt-2"
            >
              <v-icon dark>mdi-minus</v-icon>
            </v-btn>
          </div>
        </div>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
        <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

</template>

<script>
import AddProgramDialog from '../Program/create'
import Program from '../../models/Program'
import ProgramEdition from '../../models/ProgramEdition'
import ProgramEditionSchedule from '../../models/ProgramEditionSchedule'
import Model from '../../models/Model'
import alert from '../../plugins/toast'
import map from 'lodash-es/map'

export default {
  name: 'program-edition-create',

  components: {
    AddProgramDialog
  },

  model: {
    prop: 'programEdition',
    event: 'input'
  },

  props: {
    programEdition: {
      type: Model,
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

    programs: [],
    startsAtActive: false,
    endsAtActive: false,
    addProgramDialogVisible: false,
  }),

  computed: {
    dataVisible: {
      get () {
        return this.visible
      },
      set (value) {
        if (! value) {
          this.$emit('close')
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
          v => !!v || 'É obrigatória a indicação de um valor para o campo.'
        ],
        program_id: [
          v => !!v || 'É obrigatória a indicação de um valor para o campo.'
        ]
      }
    },
    managerName() {
      return this.dataProgramEdition.manager?.name
    },
  },

  methods: {
    close() {
      this.dataVisible = false
    },
    async save() {
      this.isSaving = true

      try {
        let programEdition = await this.dataProgramEdition.save()
        this.isSaving = false
        this.close()
        this.$emit('input', programEdition)
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
    }
  },

  watch: {
    programEdition: function(value) {
      this.dataProgramEdition = value
    },
    visible: function(value) {
      this.dataVisible = value
    },
  },

  mounted: async function () {
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
}
</script>

<style>
  .money input {
    text-align: end;
  }
</style>
