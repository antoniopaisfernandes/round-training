<template>
  <c-data-table>
    <v-btn v-show="list.length > 0" color="primary" class="mb-10 tw-self-end" @click.stop="newItem">Add student</v-btn>

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
      v-model:options="options"
      :items-length="totalItems"
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
      <h1 class="tw-font-bold tw-text-lg">No students yet.</h1>
      <v-btn color="primary" class="mt-10 tw-block" @click="createVisible=true">Add student</v-btn>
    </div>

  </c-data-table>
</template>

<script>
import DefaultListMixin from '../DefaultListMixin'
import cDataTable from '../Generic/Table'
import createDialog from './create'
import Student from '../../models/Student'
import alert from '../../plugins/toast'

export default {
  mixins: [DefaultListMixin],

  components: {
    createDialog,
    cDataTable,
  },

  data: () => ({
    headers: [
      {
        text: 'Name',
        align: 'start',
        sortable: true,
        value: 'name',
      },
      {
        text: 'Company',
        align: 'start',
        sortable: true,
        value: 'company.name',
      },
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    editedItem: new Student(),
    defaultItem: new Student(),
    createVisible: false,
  }),

  methods: {
    instance(attributes) {
      return new Student(attributes)
    }
  },

}
</script>
