<template>
  <v-dialog
    v-model="show"
    max-width="500px"
  >
    <v-card :loading="isSaving">

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
        <v-btn color="blue darken-1" text @click="cancel">Cancelar</v-btn>
        <v-btn color="blue darken-1" text :disabled="isSaveDisabled" @click="save">{{ commitButton }}</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>

<script>
import isObject from 'lodash-es/isObject'
import alert from '../../plugins/toast'

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
      selectedProgram: null,
      isSaving: false
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
      return this.selectedProgram === null || this.selectedProgram == ''
    },
    commitButton() {
      return isObject(this.selectedProgram) ? 'Ok' : 'Guardar'
    }
  },

  methods: {
    async save() {
      if (! this.selectedProgram) {
        this.cancel()
        return
      }

      if (isObject(this.selectedProgram)) {
        this.close()
        this.$emit('input', {
          id: this.selectedProgram.value,
          name: this.selectedProgram.text,
        })
        return
      }

      this.isSaving = true

      try {
        let response = await axios.post('/programs', {
          name: this.selectedProgram
        });

        this.cancel()
        this.$emit('input', response.data)
      } catch (error) {
          this.isSaving = false
          console.warn(error?.response?.data?.errors) // TODO
          alert.error(error)
      }
    },

    cancel() {
      this.close()
      this.selectedProgram = null
    },

    close() {
      this.show = false
    }
  }

}
</script>
