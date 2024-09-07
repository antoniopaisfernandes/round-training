import mitt from 'mitt'

const bus = mitt()

export default {
    install: (app) => {
        app.config.globalProperties.$bus = bus
    },
}

export { bus }
