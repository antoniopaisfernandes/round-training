<template>
  <div class="program-edition-index tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" @click.stop="newProgramEdition">Novo Curso</v-btn>

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
      <h1 class="tw-font-bold tw-text-lg">Ainda não existem edições de cursos.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="createVisible = true">Novo Curso</v-btn>
    </div>
  </div>
</template>

<script>
  import DefaultListMixin from '../DefaultListMixin'
  import createDialog from './create'
  import ProgramEdition from '../../models/ProgramEdition'

  export default {
    mixins: [DefaultListMixin],

    components: {
      createDialog
    },

    data: () => ({
      headers: [
        {
          text: 'Curso',
          align: 'start',
          sortable: true,
          value: 'program.name',
        },
        {
          text: 'Edição',
          align: 'start',
          sortable: true,
          value: 'name',
        },
        {
          text: 'Data início',
          align: 'start',
          sortable: true,
          value: 'starts_at',
        },
        {
          text: 'Data fim',
          align: 'start',
          sortable: true,
          value: 'ends_at',
        },
        {
          text: 'Inscritos',
          align: 'start',
          sortable: true,
          value: 'students_count',
        },
        {
          text: 'Acções',
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

<style lang="scss">
  .program-edition-index {
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
