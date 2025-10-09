import mitt from 'mitt'

const emitter = mitt()

window.Event = new class {
  constructor() {
    this.emitter = emitter
  }

  fire(event, ...args) {
    this.emitter.emit(event, ...args)
  }

  listen(event, callback) {
    this.emitter.on(event, callback)
  }
}

window.event = function (event, ...args) {
  Event.fire(event, ...args)
}

export default window.Event
