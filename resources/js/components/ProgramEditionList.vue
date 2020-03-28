<template>
  <div class="program-edition-list tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-dialog v-model="dialog" @keydown.esc="dialog = false" max-width="48rem">
      <template v-slot:activator="{ on }">
        <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" v-on="on">Novo Curso</v-btn>
      </template>

      <v-card :loading="isSaving" class="px-5 py-5">
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
                @click="addProgram"
              >
                <v-icon dark>mdi-plus</v-icon>
              </v-btn>
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
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
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
  import DefaultListMixin from './DefaultListMixin'
  import map from 'lodash-es/map'

  export default {
    mixins: [DefaultListMixin],

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
      editedItem: {
        name: '',
        manager: {},
      },
      defaultItem: {
        name: '',
        manager: {},
      },
      programs: [],
      startsAtActive: false,
      endsAtActive: false,
    }),

    computed: {
      isSaveDisabled() {
        return this.editedItem.name === '' || this.isSaving;
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
      addProgram() {
        alert('ola')
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
  .program-edition-list {
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
