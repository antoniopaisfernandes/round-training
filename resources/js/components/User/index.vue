<template>
  <c-data-table>
    <v-btn v-show="list.length > 0" color="primary" class="mb-10 tw-self-end" @click.stop="newItem">Add user</v-btn>

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
      class="elevation-1"
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
      <h1 class="tw-font-bold tw-text-lg">Ainda nao existem alunos.</h1>
      <v-btn color="primary" class="mt-10 tw-block" @click="createVisible=true">Add user</v-btn>
    </div>

  </c-data-table>
</template>

<script>
import DefaultListMixin from '../DefaultListMixin'
import cDataTable from '../Generic/Table.vue'
import createDialog from './create.vue'
import User from '../../models/User'
import alert from '../../plugins/toast.js'

export default {
  mixins: [DefaultListMixin],

  components: {
    createDialog,
    cDataTable,
  },

  data: () => ({
    headers: [
      {
        title: 'Name',
        align: 'start',
        sortable: true,
        key: 'name',
      },
      {
        title: 'Email',
        align: 'start',
        sortable: true,
        key: 'email',
      },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    editedItem: new User(),
    defaultItem: new User(),
    createVisible: false,
  }),

  methods: {
    instance(attributes) {
      return new User(attributes)
    }
  },

}
</script>
