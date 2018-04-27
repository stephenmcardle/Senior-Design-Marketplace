const vertex = require('vertex360')({site_id: process.env.TURBO_APP_ID})
const router = vertex.router()
const controllers = require('../controllers')
const csv = require('../util').createFile

const USER_NOT_LOGGED_IN = 'User%20Not%20Logged%20In'

// This route handles the entry page for the application.
// It loads the 4 most recent projects that have been approved to show to the public
router.get('/', (req, res) => {
	req.query = {
		status: 'approved'
	}
	controllers.project.get(req.query)
	.then(data => {
		data = data.slice(0,4)
		res.render('landing', {projects: data});
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})
})

// This template does not load unless the user is logged in.
// If not, it routes to the 'login' page
// This loads the management dashboard for the different users
router.get('/dashboard', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.user.getById(req.vertexSession.user.id) // Get the current user's information
	.then(user => {
		if (user.role === 'admin') { // If the current user is the admin
			controllers.project.get({ status: 'pending' }) // Get pending projects
			.then(projects => {
				controllers.projectApp.get({valid:'false'}) // Get invalid (pending) project applications
				.then(projectApp => {
					const data = {
						user: user,
						projects: projects,
						projectApp:projectApp
					}
					res.render('admin/dashboard', data) // Serve content
				})
			})
		} else if (user.role === 'instructor') { // If the current user is an instructor
			controllers.project.get({ department: user.major }) // Get projects from the instructor's department
			.then(projects => {
				controllers.user.get({ major: user.major, role: 'student'}) // Get students from the instructor's department
				.then(students => {
					const data = {
						user: user,
						projects: projects,
						students: students
					}
					res.render('instructor/dashboard', data) // Serve content
				})
			})
		} else if (user.role === 'advisor') { // If the current user is an advisor
			controllers.project.get({ advisor: req.vertexSession.user.id }) // Get the advisor's projects
			.then(projects => {
				const data = {
					user: user,
					projects: projects
				}
				res.render('advisor/dashboard', data) // Serve content
			})
		} else { // If the current user is a student
			controllers.projectApp.get({theUserID:user.id,valid:false}) // Load the student's invalid (pending) project applications
				.then(projectApp => {
					controllers.projectApp.get({theUserID:user.id,valid:true}) // Load the student's valid (approved) project applications
						.then(projectAppTrue => {
							res.render('student/dashboard', {user: user,ourapps:projectApp,ourappstrue:projectAppTrue}) // Serve content
						})
				}) // user data passed in as "user" key for Mustache rendering
		}
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message) // Redirect to error page
	})
})

// This route handles the login page
router.get('/login', (req, res) => {
	// If the user is already logged in, render the user's dashboard
	if (req.vertexSession && req.vertexSession.user) {
		res.redirect('/dashboard')
		return
	}
	res.render('login', null); // Serve login page
})

// This route handles the account registration page
router.get('/register', (req, res) => {
	// If the user is already logged in, render the user's dashboard
	if (req.vertexSession && req.vertexSession.user) {
		res.redirect('/dashboard')
		return
	}
	res.render('register', null); // Serve registration page
})

// This route handles the public user profiles for all users
// Requires user to be logged in
router.get('/users', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.user.get(req.query) // Get users from datastore
	.then(data => {
		res.render('users', {profiles: data}) // Serve content
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message) // Redirect to error page
	})
})

// This route handles the public user profile for a single user
// Requires user to be logged in
router.get('/user/:username', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.user.get({username:req.params.username}) // Get user from datastore by specified username
	.then(data => {
		if (data.length == 0){ // not found, throw error
			throw new Error('User not found.')
			return
		}

		const profile = data[0]

		/*
			This feature is currently incomplete.
			The idea is to fetch the user's teammates, i.e. other people who are registered to the same project,
			and render them as "connections" on the user's profile.
			If desired and agreed upon by stakeholders, this feature can be removed.
		*/
		controllers.user.get(req.query) // Get user connections, req.query must be updated to reflect this action
		.then(data => {
			res.render('user', {profile: profile, users: data}) // serve content
		})
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message) // Redirect to error page
	})
})

// This route handles the page which displays all projects 
// Changes based on user's role
// Requires user to be logged in
router.get('/projects', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	// Set req.query to only show approved projects
	// This can be changed to an object other than req.query if desired.
	req.query = {
		status: 'approved'
	}

	controllers.user.getById(req.vertexSession.user.id) // Get the current user
	.then(user => {
		if (user.role === 'instructor') { // If the user is an instructor
			controllers.project.get() // Get all projects
			.then(data => {
				data.forEach(project => {
					project.timestamp = project.timestamp.split('T')[0] // Only keep the date from the timestamp
				})
				res.render('instructor/projects', {projects: data}) // Serve content
			})
		} else if (user.role === 'admin') { // If the user is the admin
			controllers.project.get() // Get all projects
			.then(data => {
				data.forEach(project => {
					project.timestamp = project.timestamp.split('T')[0] // Only keep the date from the timestamp
				})
				res.render('admin/projects', {projects: data}) // Serve content
			})
		} else { // If the user is a student or advisor
			controllers.project.get(req.query) // Get approved projects
			.then(data => {
				data.forEach(project => {
					project.tags = project.tags.join(', '); // Join the tags array to be displayed properly
					project.timestamp = project.timestamp.split('T')[0] // Only keep the date from the timestamp
				})
				res.render('projects', {projects: data}) // Serve content
			})
		}
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message) // Redirect to error page
	})
})

// This route handles the page for a single project, based on its slug
// Requires user to be logged in
router.get('/project/:slug', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.project.get({slug:req.params.slug}) // Get project based on slug
	.then(data => {
		if (data.length == 0){ // not found, throw error
			throw new Error('Listing not found.')
			return
		}

		const project = data[0]
		project.tags = project.tags.join(', ')						// Join the project tags
		project.timestamp = project.timestamp.split('T')[0]			// Only keep the date from the timestamp
		controllers.user.getById(project.creatorID)					// Get the creator of the project
		.then(user => {
			controllers.user.getById(req.vertexSession.user.id)		// Get the current user
			.then(ouruser => {
				project.firstName = user.firstName
				project.lastName = user.lastName
				project.username = user.username
				res.render('project', {project: project,user:ouruser}) // Serve content
			})
		})
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})
})

// This route handles the page for instructors to edit their projects
// Requires user to be logged in
// Requires user to be an instructor
// Perhaps the admin should be given access to edit any project, but that is not the current workflow
router.get('/editProject/:slug', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.user.getById(req.vertexSession.user.id)	// Get the current user
	.then(user => {
		if (user.role === 'instructor') {	// If the user is an instructor
			controllers.project.get({slug:req.params.slug})	// Get the project based on its slug
			.then(projects => {
				if (projects.length == 0){ // not found, throw error
					throw new Error('Listing not found.')
					return
				}
				const project = projects[0]
				if (project.department === user.major) { // Ensure the instructor owns this project
					project.tags = project.tags.join(', ') // Join the project tags
					project.timestamp = project.timestamp.split('T')[0]	// Only keep the date from the timestamp
					controllers.projectApp.get({project_name:project.name,valid:false}) // Get applications to this project which have not been approved
					.then(applications => {
						controllers.user.get({ role: 'advisor' }) // Get all advisors, so the instructor can choose one to assign
						.then(advisors => {
							res.render('instructor/editProject', { project:project, projectApp:applications, advisors:advisors }) // Serve content
						})
					})
				} else { // User does not own this project
					res.redirect('/error?message=Not%20Authorized') // Redirect to error page with message "Not Authorized"
				}
			})
		} else { // User is not an instructor
			res.redirect('/error?message=Not%20Authorized') // Redirect to error page with message "Not Authorized"
		}
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message) // Redirect to error page
	})
})

// This route handles the page which allows users to propose a project
// Requires the user to be logged in
router.get('/addProject', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	const data = { id: req.vertexSession.user.id } // Serve the user's ID so we know who created the project

	res.render('addProject', data); // Serve content

})

// This route handles the about page
// Right now, the about page just has some filler content
// It can be removed without disrupting the rest of the project
// Perhas a user guide should be put up in place of the about page
router.get('/about', (req, res) => {
	res.render('about', null);
})

// This route allows instructors and admins to export data from their dashboards.
router.get('/export', (req, res) => {
	controllers.user.getById(req.vertexSession.user.id) // Get the current user
	.then(user => {
		if (user.role === 'instructor') { // If the user is an instructor
			csv(user.major) // Create a csv file based on the instructors department
			.then(() => {
				res.sendFile('download.csv', { root: './'}); // Send the file
			})
		} else if (user.role === 'admin') { // If the user is an admin
			csv('admin') // Create a csv file for all departments
			.then(() => {
				res.sendFile('download.csv', { root: '../'}); // Send the file
			})
		} else { // If the user is not an instructor or admin
			res.redirect('/error?message=Not%20Authorized'); // Redirect to error page with message "Not Authorized"
		}
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message) // Redirect to error page
	})	
})


// this page handles general errors. the error message is passed
// in as a query parameter with key "message" and rendered in the 
// template via Mustache templating:
router.get('/error', (req, res) => {
	res.render('error', {message: req.query.message})
})

// This is for static pages, and it is currently not used
router.get('/:page', (req, res) => {
	const page = staticPages[req.params.page]
	if (page == null){
		res.render('error', {message: 'Page not found'})
		return
	}

	res.render(page, null)
})


module.exports = router
