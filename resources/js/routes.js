const menuRoutes = [
    {
        type: 'v-list-item',
        icon: 'mdi-monitor-dashboard',
        text: 'Dashboard',
        link: '/',
    },
    {
        // TODO: extract to components
        type: 'v-divider',
    },
    {
        type: 'v-list-item',
        icon: 'mdi-domain',
        text: 'Companies',
        link: '/companies',
        admin: true,
    },
    {
        type: 'v-list-item',
        icon: 'mdi-account-edit-outline',
        text: 'Students',
        link: '/students',
    },
    {
        type: 'v-list-item',
        icon: 'mdi-brain',
        text: 'Program editions',
        link: '/program-editions',
    },
    // {
    //     type: 'v-list-item',
    //     icon: 'mdi-book-account-outline',
    //     text: 'Inscrições',
    //     link: '',
    // },
    {
        type: 'v-list-item',
        icon: 'mdi-microsoft-excel',
        text: 'Reports',
        link: '/reports',
    },
]

const footerRoutes = [
    {
        type: 'v-list-item',
        icon: 'mdi-account-key-outline',
        text: 'User management',
        link: '/admin/users',
        admin: true,
    },
    {
        type: 'v-list-item',
        icon: 'mdi-account-circle-outline',
        text: 'Logout',
        link: '/logout'
    }
]

export default menuRoutes
export { footerRoutes }
