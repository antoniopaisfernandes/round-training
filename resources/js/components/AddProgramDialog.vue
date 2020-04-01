<template>
  <v-dialog
    v-model="show"
    max-width="500px"
  >
    <v-card>

      <v-card-title>
        Adicionar nome de curso
      </v-card-title>

      <v-card-text>
        <v-combobox
          v-model="selectedProgram"
          :items="existingPrograms"
        ></v-combobox>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
        <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">Guardar</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'add-program-dialog',

  model: {
    prop: 'selectedProgram',
    event: 'input'
  },

  props: {
    visible: {
      type: Boolean,
      default: false
    },

    existingPrograms: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      selectedProgram: null
    }
  },

  computed: {
    show: {
      get () {
        return this.visible
      },
      set (value) {
        if (!value) {
          this.$emit('close')
        }
      }
    },
    isSaveDisabled() {
      return this.selectedProgram === null
    }
  },

  methods: {
    save() {
      // TODO axios
      this.close()
      this.$emit('input', this.selectedProgram)
      // this.$nextTick(() => this.selectedProgram = null)
    },

    cancel() {
      this.close()
      this.selectedProgram = null
      this.$emit('input', this.selectedProgram)
    },

    close() {
      this.show = false
    }
  }

}
</script>
