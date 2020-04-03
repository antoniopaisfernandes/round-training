<template>
  <div class="company-index tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-dialog v-model="dialog" @keydown.esc="dialog = false" max-width="500px">
      <template v-slot:activator="{ on }">
        <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" v-on="on">Nova Empresa</v-btn>
      </template>
      <v-card :loading="isSaving" class="px-5 py-5">
        <v-card-title>
          <span class="headline">Empresa</span>
        </v-card-title>
        <v-card-text class="mt-5">
          <v-text-field
            autofocus
            v-model="editedItem.name"
            label="Nome"
            required
            :rules="rules.name"
          ></v-text-field>
          <v-text-field
            v-model="editedItem.vat_number"
            label="Contribuinte"
            required
            :rules="rules.vat_number"
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
      <h1 class="tw-font-bold tw-text-lg">Ainda não existem empresas.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="dialog=true">Nova Empresa</v-btn>
    </div>
  </div>
</template>

<script>
  import DefaultListMixin from '../DefaultListMixin'

  export default {
    mixins: [DefaultListMixin],

    data: () => ({
      endpoint: '/companies',
      headers: [
        {
          text: 'Nome',
          align: 'start',
          sortable: true,
          value: 'name',
        },
        {
          text: 'Contribuinte',
          align: 'start',
          sortable: true,
          value: 'vat_number',
        },
        { text: 'Acções', value: 'actions', sortable: false },
      ],
      editedItem: {
        name: '',
        vat_number: '',
      },
      defaultItem: {
        name: '',
        vat_number: '',
      },
    }),

    computed: {
      isSaveDisabled() {
        return this.editedItem.name === ''
          || this.editedItem.vat_number === ''
          || this.isSaving;
      },
      rules() {
        return {
          name: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ],
          vat_number: [
            v => !!v || 'É obrigatória a indicação de um valor para o campo.'
          ]
        }
      }
    },
  }
</script>

<style lang="scss">
  .company-index {
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
