<template>
  <c-data-table>
    <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" @click.stop="newProgramEdition">New Program Edition</v-btn>

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
      :options.sync="options"
      :server-items-length="totalItems"
      :loading="isLoading"
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
      <h1 class="tw-font-bold tw-text-lg">No program editions yet.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="createVisible = true">New Program Edition</v-btn>
    </div>

  </c-data-table>
</template>

<script>
import DefaultListMixin from '../DefaultListMixin'
import cDataTable from '../Generic/Table'
import createDialog from './create'
import ProgramEdition from '../../models/ProgramEdition'

export default {
  mixins: [DefaultListMixin],

  components: {
    createDialog,
    cDataTable,
  },

  data: () => ({
    headers: [
      {
        text: 'Program',
        align: 'start',
        sortable: true,
        value: 'program.name',
      },
      {
        text: 'Edition',
        align: 'start',
        sortable: true,
        value: 'name',
      },
      {
        text: 'Start date',
        align: 'start',
        sortable: true,
        value: 'starts_at',
      },
      {
        text: 'End date',
        align: 'start',
        sortable: true,
        value: 'ends_at',
      },
      {
        text: 'Students',
        align: 'start',
        sortable: true,
        value: 'students_count',
      },
      {
        text: 'Actions',
        value: 'actions',
        sortable: false,
      },
    ],
    editedItem: new ProgramEdition(),
    defaultItem: new ProgramEdition(),
  }),

  methods: {
    instance(attributes) {
      return new ProgramEdition(attributes);
    },
    newProgramEdition() {
      this.editedItem = this.defaultItem
      this.createVisible = true
    },
  },
}
</script>
