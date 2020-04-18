describe('Authentication', () => {

    it('shows the login page when accessing the homepage without auth', () => {
        cy.visit('/').location().should((location) => {
            expect(location.pathname).to.eq('/login')
        })
    })

});
