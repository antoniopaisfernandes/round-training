import Vue from 'vue'

window.Event = new class {
    constructor() {
        this.vue = new Vue();
    }

    fire(event, ...args) {
        this.vue.$emit(event, ...args)
    }

    listen(event, callback) {
        this.vue.$on(event, callback)
    }
}

window.event = function (event, ...args) {
    Event.fire(event, ...args)
}

export default window.Event
