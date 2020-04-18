const fs = require('fs')

function teardown(projectRoot) {
  if (! projectRoot) {
    throw new Error('Project root must be an argument')
  }

  const defaultEnvFile = `${projectRoot}/.env`
  const backupEnvFile = `${projectRoot}/.env.backed_up_by_cypress`

  if (! fs.existsSync(backupEnvFile)) {
    throw new Error('There should exist a backup to restore...')
  }
  fs.renameSync(backupEnvFile, defaultEnvFile)
}

teardown(process.cwd())
