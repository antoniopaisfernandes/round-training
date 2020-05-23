<template>
  <c-data-table>
    <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" @click.stop="newItem">Nova Empresa</v-btn>

    <create-dialog
      v-model="editedItem"
      :visible="createVisible"
      @saved="saved($event)"
      @close="createVisible = false"
    ></create-dialog>

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
      <h1 class="tw-font-bold tw-text-lg">Ainda não existem empresas.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="createVisible=true">Nova Empresa</v-btn>
    </div>

  </c-data-table>
</template>

<script>
import DefaultListMixin from '../DefaultListMixin'
import cDataTable from '../Generic/Table'
import createDialog from './create'
import Company from '../../models/Company'

export default {
  mixins: [DefaultListMixin],

  components: {
    createDialog,
    cDataTable,
  },

  data: () => ({
    headers: [
      {
        text: 'Nome',
        align: 'start',
        sortable: true,
        value: 'name',
      },
      {
        text: 'Coordenador',
        align: 'start',
        sortable: true,
        value: 'coordinator.name',
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
    instance(attributes) {
      return new Company(attributes)
    }
  },
}
</script>
