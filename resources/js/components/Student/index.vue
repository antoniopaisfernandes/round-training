<template>
  <div class="student-index tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-dialog v-model="dialog" @keydown.esc="dialog = false" max-width="48rem">
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
            prepend-icon="mdi-account-edit-outline"
            required
            :rules="rules.name"
          ></v-text-field>
          <v-text-field
            v-model="editedItem.address"
            label="Morada"
            prepend-icon="mdi-map-marker"
            required
            :rules="rules.address"
          ></v-text-field>
          <div class="tw-flex">
            <v-text-field
              v-model="editedItem.postal_code"
              label="C.Postal"
              prepend-icon="mdi-city"
              required
              class="tw-w-1/4"
              :rules="rules.postal_code"
            ></v-text-field>
            <v-text-field
              v-model="editedItem.city"
              label="Localidade"
              prepend-icon="mdi-city"
              required
              class="tw-w-3/4 tw-ml-2"
              :rules="rules.city"
            ></v-text-field>
          </div>
          <div v-if="rgpd" class="tw-flex">
            <v-text-field
              v-model="editedItem.citizen_id"
              label="Cartão de cidadão"
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
                  v-model="editedItem.citizen_id_validity"
                  label="Validade"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-on="on"
                  class="tw-w-1/4 tw-ml-2"
                  :rules="rules.citizen_id_validity"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="editedItem.citizen_id_validity"
                @input="citizenIdValidityDatePickerActive = false"
              ></v-date-picker>
            </v-menu>
          </div>
          <v-text-field
            v-model="editedItem.email"
            label="E-mail"
            type="email"
            prepend-icon="mdi-email-outline"
            required
            :rules="rules.email"
          ></v-text-field>
          <div v-if="rgpd" class="tw-flex">
            <v-text-field
              v-model="editedItem.birth_place"
              label="Naturalidade"
              prepend-icon="mdi-map-marker"
              required
              :rules="rules.birth_place"
            ></v-text-field>
            <v-text-field
              v-model="editedItem.nationality"
              label="Nacionalidade"
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
            v-model="editedItem.company.id"
            label="Empresa"
            prepend-icon="mdi-factory"
            required
            :rules="rules.company"
            @input="editedItem.current_company_id = $event"
          ></v-select>
          <v-text-field
            v-model="editedItem.current_job_title"
            label="Função"
            prepend-icon="mdi-barcode-scan"
            required
            :rules="rules.current_job_title"
          ></v-text-field>
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
  import DefaultListMixin from '../DefaultListMixin'
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
      citizenIdValidityDatePickerActive: false,
    }),

    computed: {
      isSaveDisabled() {
        return this.editedItem.name === ''
          || this.editedItem.company === ''
          || this.isSaving;
      },
      rgpd() {
        return this.editedItem.hasOwnProperty('citizen_id')
          && this.editedItem.hasOwnProperty('citizen_id_validity')
      },
      rules() {
        return {
          name: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ],
          company: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ],
          address: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ],
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
  .student-index {
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
