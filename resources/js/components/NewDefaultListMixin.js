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
        this.list = this.items.map((c) => new Company(c))
    },

    methods: {
        editItem(item) {
            this.editedIndex = this.list.indexOf(item)
            this.editedItem = item.clone()
            this.createVisible = true
        },

        async save() {
            this.isSaving = true

            try {
                if (this.editedIndex > -1) {
                    const response = await axios.put(
                        `${this.endpoint}/${this.editedItem.id}`,
                        this.editedItem
                    )
                    Object.assign(
                        this.list[this.editedIndex],
                        response.data
                    )
                } else {
                    const response = await axios.post(this.endpoint, this.editedItem)
                    this.list.push(response.data)
                }
                this.isSaving = false
                this.dialog = false
            } catch (error) {
                this.isSaving = false
                console.warn(error?.response?.data?.errors) // TODO
                alert.error(error)
            }
        },

        async deleteItem(item) {
            const index = this.list.indexOf(item)

            if (!confirm('Tem a certeza que pretende remover este registo?')) {
                return
            }

            try {
                const response = await axios.delete(
                    `${this.endpoint}/${item.id}`
                )
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
