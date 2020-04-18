describe('Login', () => {

    it('shows the login page', () => {
        cy.visit('/login').contains('Login')
    });

    it('displays an error when using invalid credentials', () => {
        cy.visit('/login');

        cy.get('#email').type('error@esferasaude.pt');
        cy.get('#password').type('wrong_password');
        cy.get('button').contains('Login').click();

        cy.contains('O seu utilizador ou password estão incorrectos.')
    });


    it('can login using seed credentials', () => {
        cy.visit('/login');

        cy.get('#email').type('admin@esferasaude.pt');
        cy.get('#password').type('password');
        cy.get('button').contains('Login').click();

        cy.contains('Cursos terminados');
        cy.location().should((location) => {
            expect(location.pathname).to.eq('/')
        })
    });

});
