<template>
  <div class="program-edition-index tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" @click.stop="dialog = true">Novo Curso</v-btn>

    <v-dialog persistent scrollable v-model="dialog" @keydown.esc="dialog = false" max-width="48rem">
      <v-card :loading="isSaving" class="px-5 py-5" height="90vh">
        <v-card-title>
          <span class="headline">Curso</span>
        </v-card-title>
        <v-card-text class="mt-5">
          <div class="tw-flex">
            <div class="tw-flex tw-flex-row tw-justify-center tw-items-center tw-w-2/3">

              <v-select
                :items="programs"
                v-model="editedItem.program_id"
                label="Nome do curso"
                required
                :rules="rules.program"
                @input="editedItem.program_id = $event"
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
              v-model="editedItem.name"
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
                  v-model="editedItem.starts_at"
                  label="Início a"
                  prepend-inner-icon="mdi-calendar"
                  readonly
                  v-on="on"
                  :rules="rules.starts_at"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="editedItem.starts_at"
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
                  v-model="editedItem.ends_at"
                  label="Fim a"
                  prepend-inner-icon="mdi-calendar"
                  readonly
                  v-on="on"
                  class="tw-ml-2"
                  :rules="rules.ends_at"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="editedItem.ends_at"
                @input="endsAtActive = false"
              ></v-date-picker>
            </v-menu>
            <v-text-field
              v-model="editedItem.manager.name"
              label="Criado por"
              :disabled="true"
              class="ml-2"
            ></v-text-field>
          </div>
          <div class="tw-flex tw-flex-row tw-justify-center tw-items-center">
            <v-text-field
              v-model="editedItem.supplier"
              label="Fornecedor"
              required
              :rules="rules.supplier"
            ></v-text-field>
            <v-text-field
              v-model="editedItem.teacher_name"
              label="Formador"
              required
              :rules="rules.teacher_name"
              class="ml-2"
            ></v-text-field>
            <v-text-field
              v-model="editedItem.cost"
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

          <div v-bind:key="i" v-for="(schedule, i) in editedItem.schedules" class="tw-flex">
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
          <v-btn color="blue darken-1" text @click="dialog = false">Cancelar</v-btn>
          <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-data-table
      v-if="list.length"
      :headers="headers"
      :fixed-header="true"
      :items="list"
      sort-by="name"
      class="elevation-1"
    >
      <template v-slot:item.actions="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editItem(item)"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          small
          @click="deleteItem(item)"
        >
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>

    <div v-else class="tw-flex tw-flex-col tw-content-center tw-items-center mt-50">
      <h1 class="tw-font-bold tw-text-lg">Ainda não existem edições de cursos.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="dialog=true">Novo Curso</v-btn>
    </div>
  </div>
</template>

<script>
  import DefaultListMixin from '../DefaultListMixin'
  import AddProgramDialog from '../Program/create'
  import ProgramEdition from '../../models/ProgramEdition'
  import ProgramEditionSchedule from '../../models/ProgramEditionSchedule'
  import map from 'lodash-es/map'

  export default {
    name: 'ProgramEditionList',

    mixins: [DefaultListMixin],

    components: {
      AddProgramDialog
    },

    data: () => ({
      endpoint: '/program-editions',
      headers: [
        {
          text: 'Curso',
          align: 'start',
          sortable: true,
          value: 'program.name',
        },
        {
          text: 'Edição',
          align: 'start',
          sortable: true,
          value: 'name',
        },
        {
          text: 'Data início',
          align: 'start',
          sortable: true,
          value: 'starts_at',
        },
        {
          text: 'Data fim',
          align: 'start',
          sortable: true,
          value: 'ends_at',
        },
        {
          text: 'Inscritos',
          align: 'start',
          sortable: true,
          value: 'students_count',
        },
        {
          text: 'Acções',
          value: 'actions',
          sortable: false,
        },
      ],
      editedItem: new ProgramEdition(),
      defaultItem: new ProgramEdition(),
      programs: [],

      // UI
      startsAtActive: false,
      endsAtActive: false,
      addProgramDialogVisible: false,
    }),

    computed: {
      isSaveDisabled() {
        return this.isSaving
          // || this.editedItem.name === ''
        ;
      },
      rules() {
        return {
          name: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ],
          program: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ],
        }
      }
    },

    methods: {
      // Program names
      selectProgramId(program) {
        if (! program) {
          return
        }

        this.programs.push({
          text: program.name,
          value: program.id
        })
        this.editedItem.program_id = program.id
      },

      // Schedules
      addSchedule() {
        this.editedItem.schedules.push(new ProgramEditionSchedule)
      },
      deleteSchedule(index) {
        this.editedItem.schedules.splice(index, 1)
      }
    },

    mounted: async function () {
      let data = await this.fetchPrograms()

      this.programs = map(data, (v) => {
        return {
          'text': v.name,
          'value': v.id,
        }
      })
    }
  }
</script>

<style lang="scss">
  .program-edition-index {
    table th {
      text-transform: uppercase;
      letter-spacing: 1px;
      .v-icon {
        margin-left: 5px;
      }
    }

    table tr th:last-child,
    table tr td:last-child {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-end;
      padding-right: 50px;
    }
  }

  .money input {
    text-align: end;
  }
</style>
