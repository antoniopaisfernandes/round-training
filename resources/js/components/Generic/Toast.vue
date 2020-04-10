<template>
  <v-snackbar
    v-model="visible"
    :timeout="timeout"
    :top="top"
    :right="right"
    :color="color"
  >
    {{ text }}
    <v-btn
      color="blue"
      text
      @click="snackbar = false"
    >Fechar</v-btn>
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
      top: true,
      right: true
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
