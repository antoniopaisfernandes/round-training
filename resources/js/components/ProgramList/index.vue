<template>
  <div class="program-list tw-flex tw-flex-col tw-mx-5">
    <v-dialog v-model="dialog" max-width="500px">
      <template v-slot:activator="{ on }">
        <v-btn color="primary" dark class="mb-10 tw-self-end" v-on="on">Novo Programa</v-btn>
      </template>
      <v-card :loading="isSaving">
        <v-card-title>
          <span class="headline">Programa</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="editedItem.name" label="Nome"></v-text-field>
              </v-col>
            </v-row>
          </v-container>
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
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize">Reset</v-btn>
      </template>
    </v-data-table>
  </div>
</template>

<script>
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
        { text: '', value: 'actions', sortable: false },
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

        if(!confirm('Are you sure you want to delete this program?')) return

        try {
          const response = await axios.post(`/program/${item.id}`, { _method: 'DELETE' })
          this.programs.splice(index, 1)
        } catch (error) {
          console.warn(error)
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

        if (this.editedIndex > -1) {
          try {
            const response = await axios.post(`/program/${this.editedItem.id}`,
            {
              _method: 'PUT',
              name: this.editedItem.name
            })
            Object.assign(this.programs[this.editedIndex], this.editedItem)
          } catch (error) {
            console.warn(error)
          }
        } else {
          try {
            const response = await axios.post('/program', {
              name: this.editedItem.name
              })
            this.programs.push(response.data.program)
          } catch (error) {
            console.warn(error)
          }
        }

        this.isSaving = false;
        this.close()
      },
    },
  }
</script>

<style lang="scss">
  .program-list {
    table tr td:last-child {
      display: flex;
      justify-content: flex-end;
      padding-right: 30px;
    }
  }
</style>
