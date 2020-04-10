<template>
  <div class="student-index tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" @click.stop="newItem">Adicionar aluno</v-btn>

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
      <h1 class="tw-font-bold tw-text-lg">Ainda não existem alunos.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="createVisible=true">Adicionar aluno</v-btn>
    </div>
  </div>
</template>

<script>
  import DefaultListMixin from '../DefaultListMixin'
  import createDialog from './create'
  import Student from '../../models/Student'
  import alert from '../../plugins/toast'

  export default {
    mixins: [DefaultListMixin],

    components: {
      createDialog
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
          text: 'Empresa',
          align: 'start',
          sortable: true,
          value: 'company.name',
        },
        { text: 'Acções', value: 'actions', sortable: false },
      ],
      editedItem: new Student(),
      defaultItem: new Student(),
      createVisible: false,
    }),

    methods: {
      instance(attributes) {
        return new Student(attributes);
      }
    },

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
