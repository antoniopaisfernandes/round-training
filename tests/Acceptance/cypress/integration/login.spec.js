describe('Login', () => {

    // it('shows the login page', () => {
    //     cy.visit('/login').contains('Login')
    // });

    it('can login using seed credentials', () => {
        cy.visit('/login');

        cy.get('#email').type('admin@esferasaude.pt');
        cy.get('#password').type('password');

        cy.get('button').contains('Login').click();
    });

});
