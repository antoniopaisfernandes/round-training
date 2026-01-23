<template>
  <c-data-table>
    <v-btn v-show="list.length > 0" color="primary" class="mb-10 tw-self-end" @click.stop="newProgramEdition">New Program Edition</v-btn>

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
      :options="options"
      :items-length="totalItems"
      :loading="isLoading"
      @update:options="onOptionsUpdate"
    >
      <template v-slot:item.actions="{ item }">
        <v-icon
          size="small"
          class="mr-2"
          @click="editItem(item)"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          size="small"
          @click="deleteItem(item)"
        >
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>

    <div v-else class="tw-flex tw-flex-col tw-content-center tw-items-center mt-50">
      <h1 class="tw-font-bold tw-text-lg">No program editions yet.</h1>
      <v-btn color="primary" class="mt-10 tw-block" @click="createVisible = true">New Program Edition</v-btn>
    </div>

  </c-data-table>
</template>

<script>
import DefaultListMixin from '../DefaultListMixin'
import cDataTable from '../Generic/Table.vue'
import createDialog from './create.vue'
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
        title: 'Program',
        align: 'start',
        sortable: true,
        key: 'program.name',
      },
      {
        title: 'Edition',
        align: 'start',
        sortable: true,
        key: 'name',
      },
      {
        title: 'Start date',
        align: 'start',
        sortable: true,
        key: 'starts_at',
      },
      {
        title: 'End date',
        align: 'start',
        sortable: true,
        key: 'ends_at',
      },
      {
        title: 'Students',
        align: 'start',
        sortable: true,
        key: 'students_count',
      },
      {
        title: 'Actions',
        key: 'actions',
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
