<template>
  <v-dialog
    v-model="show"
    max-width="500px"
  >
    <v-card :loading="isSaving">

      <v-card-title>
        Add program name
      </v-card-title>

      <v-card-text>
        <v-combobox
          v-model="selectedProgram"
          :items="existingPrograms"
        ></v-combobox>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue -darken-1" variant="text" @click="cancel">Cancel</v-btn>
        <v-btn color="blue -darken-1" variant="text" :disabled="isSaveDisabled" @click="save">{{ commitButton }}</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>

<script>
import isObject from 'lodash-es/isObject'
import alert from '../../plugins/toast'

export default {
  name: 'add-program-dialog',


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
      return this.modelValue === null || this.modelValue == ''
    },
    commitButton() {
      return isObject(this.modelValue) ? 'Ok' : 'Save'
    }
  },

  methods: {
    async save() {
      if (! this.modelValue) {
        this.cancel()
        return
      }

      if (isObject(this.modelValue)) {
        this.close()
        this.$emit('update:modelValue', {
          id: this.modelValue.value,
          name: this.modelValue.text,
        })
        return
      }

      this.isSaving = true

      try {
        let response = await axios.post('/programs', {
          name: this.modelValue
        });

        this.cancel()
        this.$emit('update:modelValue', response.data)
      } catch (error) {
          this.isSaving = false
          console.warn(error?.response?.data?.errors) // TODO
          alert.error(error)
      }
    },

    cancel() {
      this.close()
      this.modelValue = null
    },

    close() {
      this.show = false
    }
  }

}
</script>
