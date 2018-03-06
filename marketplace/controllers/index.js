const UserController = require('./UserController')
const ProjectController = require('./ProjectController')
const ProjectAppController = require('./ProjectAppController')
module.exports = {

	user: UserController,
	project: ProjectController,
	projectApp: ProjectAppController

}