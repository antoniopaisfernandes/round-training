<template>
  <div class="student-list tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-dialog v-model="dialog" @keydown.esc="dialog = false" max-width="500px">
      <template v-slot:activator="{ on }">
        <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" v-on="on">Adicionar aluno</v-btn>
      </template>
      <v-card :loading="isSaving" class="px-5 py-5">
        <v-card-title>
          <span class="headline">Aluno</span>
        </v-card-title>
        <v-card-text class="mt-5">
          <v-text-field
            autofocus
            v-model="editedItem.name"
            label="Nome"
            required
            :rules="rules.name"
          ></v-text-field>
          <v-select
            :items="companies"
            v-model="editedItem.company.id"
            label="Empresa"
            required
            :rules="rules.company"
            @input="editedItem.current_company_id = $event"
          ></v-select>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
          <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-data-table
      :headers="headers"
      :fixed-header="true"
      :items="list"
      sort-by="name"
      class="elevation-1"
      v-if="list.length"
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
      <h1 class="tw-font-bold tw-text-lg">Ainda não existem alunos.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="dialog=true">Adicionar aluno</v-btn>
    </div>
  </div>
</template>

<script>
  import DefaultListMixin from './DefaultListMixin'
  import map from 'lodash-es/map'

  export default {
    mixins: [DefaultListMixin],

    data: () => ({
      endpoint: '/students',
      headers: [
        {
          text: 'Nome',
          align: 'start',
          sortable: true,
          value: 'name',
        },
        {
          text: 'Empresa',
          align: 'start',
          sortable: true,
          value: 'company.name',
        },
        { text: 'Acções', value: 'actions', sortable: false },
      ],
      editedItem: {
        name: '',
        current_company_id: '',
        company: {
          id: '',
          name: ''
        },
      },
      defaultItem: {
        name: '',
        current_company_id: '',
        company: {
          id: '',
          name: ''
        },
      },
      companies: [],
    }),

    computed: {
      isSaveDisabled() {
        return this.editedItem.name === ''
          || this.editedItem.company === ''
          || this.isSaving;
      },
      rules() {
        return {
          name: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ],
          company: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ]
        }
      }
    },

    mounted: async function () {
      let data = await this.fetchCompanies()

      this.companies = map(data, (v) => {
        return {
          'text': v.name,
          'value': v.id,
        }
      })
    }
  }
</script>

<style lang="scss">
  .student-list {
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
</style>
