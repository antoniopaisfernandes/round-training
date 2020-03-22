<template>
  <div class="program-list tw-flex tw-flex-col tw-mt-10 tw-mx-20">
    <v-dialog v-model="dialog" @keydown.esc="dialog = false" max-width="500px">
      <template v-slot:activator="{ on }">
        <v-btn v-show="programs.length > 0" color="primary" dark class="mb-10 tw-self-end" v-on="on">Novo Programa</v-btn>
      </template>
      <v-card :loading="isSaving" class="px-5 py-5">
        <v-card-title>
          <span class="headline">Programa</span>
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
      :items="programs"
      sort-by="name"
      class="elevation-1"
      v-if="programs.length"
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
      <h1 class="tw-font-bold tw-text-lg">Ainda não existem programas.</h1>
      <v-btn color="primary" dark class="mt-10 tw-block" @click="dialog=true">Novo Programa</v-btn>
    </div>
  </div>
</template>

<script>
  import alert from '../plugins/toast'

  export default {
    props: ['items'],
    data: () => ({
      dialog: false,
      headers: [
        {
          text: 'Nome',
          align: 'start',
          sortable: true,
          value: 'name',
        },
        { text: 'Acções', value: 'actions', sortable: false },
      ],
      programs: [],
      editedIndex: -1,
      editedItem: {
        name: '',
      },
      defaultItem: {
        name: '',
      },
      isSaving: false
    }),
    computed: {
      isSaveDisabled() {
        return this.editedItem.name === '' || this.isSaving;
      }
    },
    watch: {
      dialog (val) {
        val || this.close()
      },
    },
    created () {
      this.initialize()
    },
    methods: {
      initialize () {
        this.programs = this.items
      },
      editItem (item) {
        this.editedIndex = this.programs.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },
      async deleteItem (item) {
        const index = this.programs.indexOf(item)

        if(!confirm('Tem a certeza que pretende remover este Programa?')) return

        try {
          const response = await axios.post(`/program/${item.id}`, { _method: 'DELETE' })
          this.programs.splice(index, 1)
        } catch (error) {
          alert.error(error)
        }
      },
      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },
      async save () {
        this.isSaving = true;

        try {
          if (this.editedIndex > -1) {
            const response = await axios.post(`/program/${this.editedItem.id}`,
            {
              _method: 'PUT',
              name: this.editedItem.name
            })
            Object.assign(this.programs[this.editedIndex], this.editedItem)
          } else {
            const response = await axios.post('/program', {
              name: this.editedItem.name
              })
            this.programs.push(response.data.program)
          }
          this.isSaving = false;
          this.close()
        } catch (error) {
          this.isSaving = false;
          alert.error(error)
        }
      },
    },
  }
</script>

<style lang="scss">
  .program-list {
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
