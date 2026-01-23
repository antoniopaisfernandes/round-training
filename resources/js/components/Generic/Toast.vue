<template>
  <v-snackbar
    v-model="visible"
    :timeout="timeout"
    location="top right"
    :color="color"
  >
    {{ text }}
    <template v-slot:actions>
      <v-btn
        color="blue"
        variant="text"
        @click="visible = false"
      >Fechar</v-btn>
    </template>
  </v-snackbar>
</template>

<script>
  import colors from '../../colors'

  export default {
    data: () => ({
      visible: false,
      text: '',
      timeout: 4000,
      type: 'info',
    }),
    mounted() {
      Event.listen('toast', ({
        type = 'info',
        message = ''
      } = {}) => {
        this.text = message
        this.type = type
        this.visible = true
      })
    },
    computed: {
      color() {
        return this.type == 'warning'
          ? colors.warning
          : (
            this.type == 'error'
            ? colors.error
            : colors.primary
          )
      }
    }
  }
</script>
