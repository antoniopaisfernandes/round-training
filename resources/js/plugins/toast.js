import Event from './event-bus'

export default {
    info (message) {
        Event.fire('toast', { type: 'info', message })
    },

    success (message) {
        Event.fire('toast', { type: 'success', message })
    },

    warning (message) {
        Event.fire('toast', { type: 'warning', message })
    },

    error (message) {
        Event.fire('toast', { type: 'error', message })
    }
}
