describe('Login', () => {

    it('shows the login page', () => {
        cy.visit('/login').contains('Login')
    });

    it('displays an error when using invalid credentials', () => {
        cy.visit('/login');

        cy.get('#email').type('error@roundtraining.com');
        cy.get('#password').type('wrong_password');
        cy.get('button').contains('Login').click();

        cy.contains('These credentials do not match our records')
    });


    it('can login using seed credentials', () => {
        cy.visit('/login');

        cy.get('#email').type('admin@roundtraining.com');
        cy.get('#password').type('password');
        cy.get('button').contains('Login').click();

        cy.contains('Ended')
        cy.location().should((location) => {
            expect(location.pathname).to.eq('/')
        })
    });

});
