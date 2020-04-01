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
        <v-select
          :items="existingPrograms"
          label="A Select List"
          item-value="text"
        ></v-select>
      </v-card-text>

      <v-card-actions>
        <v-btn
          color="primary"
          text
          @click="close"
        >
        Close
        </v-btn>
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
    }
  },

  methods: {
    ok() {
      // TODO axios
      this.close()
      this.$emit('input', this.selectedProgram)
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
