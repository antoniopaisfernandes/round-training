<template>
  <div class="company-index tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" @click.stop="createVisible = true">Nova Empresa</v-btn>

    <create-dialog
      v-model="editedItem"
      :visible="createVisible"
      @saved="saved($event)"
      @close="createVisible = false"
    ></create-dialog>

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
      <v-btn color="primary" dark class="mt-10 tw-block" @click="createVisible=true">Nova Empresa</v-btn>
    </div>
  </div>
</template>

<script>
  import NewDefaultListMixin from '../NewDefaultListMixin'
  import createDialog from './create'
  import Company from '../../models/Company'

  export default {
    mixins: [NewDefaultListMixin],

    components: {
      createDialog
    },

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
      defaultItem: new Company(),
      editedItem: new Company(),
      createVisible: false
    }),

    methods: {
      instance(attributes = {}) {
        return new Company(attributes);
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
