// The functions register the hooks to run
// before or after each spec or test

before(() => {
  cy.exec('php ../../artisan migrate:fresh --seed')
})

beforeEach(() => {
  // cy.exec();
})

after(() => {
  // cy.exec();
})

afterEach(() => {
  // cy.exec();
})
