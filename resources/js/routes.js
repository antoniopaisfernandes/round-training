const menuRoutes = [
    {
        type: 'v-list-item',
        icon: 'mdi-monitor-dashboard',
        text: 'Dashboard',
        link: '',
    },
    {
        // TODO: extract to components
        type: 'v-divider',
    },
    {
        type: 'v-list-item',
        icon: 'mdi-domain',
        text: 'Empresas',
        link: '/companies',
    },
    {
        type: 'v-list-item',
        icon: 'mdi-account-edit-outline',
        text: 'Alunos',
        link: '/students',
    },
    {
        type: 'v-list-item',
        icon: 'mdi-brain',
        text: 'Cursos',
        link: '/programs',
    },
    {
        type: 'v-list-item',
        icon: 'mdi-book-account-outline',
        text: 'Inscrições',
        link: '',
    },
]

const authRoutes = [
    {
        type: 'v-list-item',
        icon: 'mdi-account-circle-outline',
        text: 'Logout',
        link: '/logout',
    }
]

export default menuRoutes
export { authRoutes }
