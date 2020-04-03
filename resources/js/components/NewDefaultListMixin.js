import alert from '../plugins/toast'
import cloneDeep from 'lodash-es/cloneDeep'
import Program from '../models/Program'
import Company from '../models/Company'

export default {
    props: ['items'],

    data: () => ({
        endpoint: null,
        list: [],
        editedIndex: -1,
        isSaving: false,
        dialog: false,
        createVisible: false,
    }),

    watch: {
        dialog(val) {
            val || this.close()
        }
    },

    created() {
        this.list = this.items.map((c) => this.instance(c))
    },

    methods: {
        instance(attributes) {
            throw Error('Instance method must be defined in parent')
        },

        editItem(item) {
            this.editedIndex = this.list.indexOf(item)
            this.editedItem = item.clone()
            this.createVisible = true
        },

        saved(item) {
            if (this.editedIndex > -1) {
                Object.assign(this.list[this.editedIndex], item)
            } else {
                this.list.push(item)
            }
            this.close()
        },

        async deleteItem(item) {
            const index = this.list.indexOf(item)

            if (!confirm('Tem a certeza que pretende remover este registo?')) {
                return
            }

            try {
                item.delete()
                this.list.splice(index, 1)
            } catch (error) {
                console.warn(error?.response?.data?.errors) // TODO
                alert.error(error)
            }
        },

        close() {
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            }, 300)
        },

        async fetchCompanies() {
            try {
                const response = Company.get()
                return response
            } catch (error) {
                console.warn(error?.response?.data?.errors) // TODO
                alert.error(error)
            }
        },

        async fetchPrograms() {
            try {
                const response = await Program.get()
                return response
            } catch (error) {
                console.warn(error?.response?.data?.errors) // TODO
                alert.error(error)
            }
        },
    }
}
