<template>
  <div class="program-edition-list tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-dialog v-model="dialog" @keydown.esc="dialog = false" max-width="500px">
      <template v-slot:activator="{ on }">
        <v-btn v-show="list.length > 0" color="primary" dark class="mb-10 tw-self-end" v-on="on">Novo Curso</v-btn>
      </template>
      <v-card :loading="isSaving" class="px-5 py-5">
        <v-card-title>
          <span class="headline">Curso</span>
        </v-card-title>
        <v-card-text class="mt-5">
          <v-text-field autofocus v-model="editedItem.name" label="Nome"></v-text-field>
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
      <h1 class="tw-font-bold tw-text-lg">Ainda não existem cursos.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="dialog=true">Novo Curso</v-btn>
    </div>
  </div>
</template>

<script>
  import DefaultListMixin from './DefaultListMixin'

  export default {
    mixins: [DefaultListMixin],

    data: () => ({
      endpoint: '/program-editions',
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
      editedItem: {
        name: '',
      },
      defaultItem: {
        name: '',
      },
    }),
    computed: {
      isSaveDisabled() {
        return this.editedItem.name === '' || this.isSaving;
      }
    },
  }
</script>

<style lang="scss">
  .program-edition-list {
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
