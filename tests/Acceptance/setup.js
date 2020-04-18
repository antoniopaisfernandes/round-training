const fs = require('fs')

function copyEnvFile(projectRoot) {
  const defaultEnvFile = `${projectRoot}/.env`
  const backupEnvFile = `${projectRoot}/.env.backed_up_by_cypress`
  const acceptanceEnvFile = `${projectRoot}/tests/Acceptance/.env.acceptance`

  if (fs.existsSync(backupEnvFile)) {
    throw new Error('It seems Cypress did not finish last time. Check you .env file to prevent loss of data.')
  }
  if (! fs.existsSync(acceptanceEnvFile)) {
    throw new Error(`The file ${acceptanceEnvFile} does not exist.`)
  }

  if (fs.existsSync(defaultEnvFile)) {
    fs.renameSync(defaultEnvFile, backupEnvFile)
  }

  fs.copyFileSync(acceptanceEnvFile, defaultEnvFile)
}

function copyFreshDatabase(projectRoot) {
  const blankDatabaseFile = `${projectRoot}/tests/Acceptance/acceptance.sqlite`
  const destinationDatabaseFile = `${projectRoot}/database/acceptance.sqlite`

  fs.copyFileSync(blankDatabaseFile, destinationDatabaseFile)
}

function setup(projectRoot) {
  if (! projectRoot) {
    throw new Error('Project root must be an argument')
  }

  copyEnvFile(projectRoot)
  copyFreshDatabase(projectRoot)
}

setup(process.cwd())
