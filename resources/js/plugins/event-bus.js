import mitt from 'mitt'

const emitter = mitt()

window.Event = {
  fire(event, ...args) {
    emitter.emit(event, ...args)
  },

  listen(event, callback) {
    emitter.on(event, callback)
  },

  off(event, callback) {
    emitter.off(event, callback)
  }
}

window.event = function (event, ...args) {
  Event.fire(event, ...args)
}

export default window.Event
