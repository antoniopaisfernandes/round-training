describe('User.Create', () => {

    it('can add roles to new users', () => {
        cy.visit('/admin/users')
        cy.get('#email').type('admin@roundtraining.com')
        cy.get('#password').type('password')
        cy.get('button').contains('Login').click()

        cy.get('button').contains('Add user').click()
        cy.get('#name').type('JohnDoe')
        cy.get('#email').type('johndoe@example.com')
        cy.get('#password').type('its_a_password')
        cy.get('#password_confirmation').type('its_a_password')
        cy.get('.v-input__control > div[role=button]').last().click()
        cy.get('.v-simple-checkbox').first().click()

        cy.get('.v-select__selections').contains('admin')
    });

});
