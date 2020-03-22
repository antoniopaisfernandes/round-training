import alert from '../plugins/toast'

export default {
    props: ['items'],

    data: () => ({
        endpoint: null,
        list: [],
        editedIndex: -1,
        isSaving: false,
        dialog: false,
    }),

    watch: {
        dialog(val) {
            val || this.close()
        }
    },

    created() {
        this.list = this.items

        if (typeof this.initialize === 'function') {
            this.initialize()
        }
    },

    methods: {
        editItem(item) {
            this.editedIndex = this.list.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        async save() {
            this.isSaving = true

            try {
                if (this.editedIndex > -1) {
                    const response = await axios.put(
                        `${this.endpoint}/${this.editedItem.id}`,
                        this.editedItem
                    )
                    Object.assign(this.list[this.editedIndex], this.editedItem)
                } else {
                    const response = await axios.post(this.endpoint, this.editedItem)
                    this.list.push(response.data.company)
                }
                this.isSaving = false
                this.close()
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
            this.dialog = false
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            }, 300)
        }
    }
}
