const vertex = require('vertex360')({site_id: process.env.TURBO_APP_ID})
const router = vertex.router()
const controllers = require('../controllers')
const csv = require('../util').createFile

const USER_NOT_LOGGED_IN = 'User%20Not%20Logged%20In'

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
	This route handles various page requests. Pages are 
	organized into static and non-static pages. Static pages
	simply render HTML with no dynamic data. Non-static pages
	require dynamic data and typically make a Turbo request
	to fetch or update the necessary data.
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// add more static pages here if necessary
const staticPages = {
	landing: 'landing'
}

// this route loads the landing/home page. It is the primary
// promotional page for the app and should strongly accentuate
// the key value proposition as well as guide the visitor to
// a prominent call-to-action registration form:
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

// this template does not load unless the user is logged in.
// If not, it routes to the 'error' template with corrensponding
// error message. The 'dashboard' template is for updating
// user profile information and general user management functions:
router.get('/dashboard', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.user.getById(req.vertexSession.user.id)
	.then(user => {
		if (user.role === 'admin') {
			controllers.project.get({ status: 'pending' })
			.then(projects => {
				controllers.projectApp.get({valid:'false'})
				.then(projectApp => {
					const data = {
						user: user,
						projects: projects,
						projectApp:projectApp
					}
					res.render('admin/dashboard', data)
				})
			})
		} else if (user.role === 'instructor') {
			controllers.project.get({ department: user.major })
			.then(projects => {
				controllers.user.get({ major: user.major, role: 'student'})
				.then(students => {
					const data = {
						user: user,
						projects: projects,
						students: students
						}
					res.render('instructor/dashboard', data)
				})
			})
		} else {
			controllers.projectApp.get({theUserID:user.id,valid:false})
				.then(projectApp => {
					controllers.projectApp.get({theUserID:user.id,valid:true})
						.then(projectAppTrue => {
							res.render('student/dashboard', {user: user,ourapps:projectApp,ourappstrue:projectAppTrue})
						})
				}) // user data passed in as "user" key for Mustache rendering
		}
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})
})

router.get('/login', (req, res) => {
	if (req.vertexSession && req.vertexSession.user) {
		res.redirect('/dashboard')
		return
	}
	res.render('login', null);
})

router.get('/register', (req, res) => {
	if (req.vertexSession && req.vertexSession.user) {
		res.redirect('/dashboard')
		return
	}
	res.render('register', null);
})

router.get('/users', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.user.get(req.query)
	.then(data => {
		res.render('users', {profiles: data})
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})
})

router.get('/user/:username', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.user.get({username:req.params.username})
	.then(data => {
		if (data.length == 0){ // not found, throw error
			throw new Error('User not found.')
			return
		}

		const profile = data[0]

		controllers.user.get(req.query)
		.then(data => {
			res.render('user', {profile: profile, users: data})
		})
		.catch(err => {
			res.redirect('/error?message=' + err.message)
		})
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})
})

router.get('/projects', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	req.query = {
		status: 'approved'
	}

	controllers.user.getById(req.vertexSession.user.id)
	.then(user => {
		if (user.role === 'instructor') {
			req.query = {}
			controllers.project.get(req.query)
			.then(data => {
				data.forEach(project => {
					project.timestamp = project.timestamp.split('T')[0]
				})
				res.render('instructor/projects', {projects: data})
			})
			.catch(err => {
				res.redirect('/error?message=' + err.message)
			})

		} else if (user.role === 'admin') {
			req.query = {}
			controllers.project.get(req.query)
			.then(data => {
				data.forEach(project => {
					project.timestamp = project.timestamp.split('T')[0]
				})
				res.render('admin/projects', {projects: data})
			})
			.catch(err => {
				res.redirect('/error?message=' + err.message)
			})
		} else {
			controllers.project.get(req.query)
			.then(data => {
				data.forEach(project => {
					project.tags = project.tags.join(', ');
					project.timestamp = project.timestamp.split('T')[0]
				})
				res.render('projects', {projects: data})
			})
			.catch(err => {
				res.redirect('/error?message=' + err.message)
			})
		}
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})
})

router.get('/project/:slug', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.project.get({slug:req.params.slug})
	.then(data => {
		if (data.length == 0){ // not found, throw error
			throw new Error('Listing not found.')
			return
		}

		const project = data[0]
		project.tags = project.tags.join(', ')
		project.timestamp = project.timestamp.split('T')[0]
		controllers.user.getById(project.creatorID)
		.then(user => {
			controllers.user.getById(req.vertexSession.user.id)
			.then(ouruser => {
			project.firstName = user.firstName
				project.lastName = user.lastName
				project.username = user.username
				res.render('project', {project: project,user:ouruser})
			})
		})
		.catch(err => {
			res.redirect('/error?message=' + err.message)
		})

	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})
})


router.get('/editProject/:slug', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	controllers.user.getById(req.vertexSession.user.id)
	.then(user => {
		if (user.role === 'instructor') {
			controllers.project.get({slug:req.params.slug})
			.then(data => {
				if (data.length == 0){ // not found, throw error
					throw new Error('Listing not found.')
					return
				}
				const project = data[0]
				if (project.department === user.major) {
					project.tags = project.tags.join(', ')
					project.timestamp = project.timestamp.split('T')[0]
					controllers.projectApp.get({project_name:project.name})
					.then(data2 => {
						res.render('instructor/editProject', {project:project,projectApp:data2})

					})
					
				} else {
					res.redirect('/error?message=Not%20Authorized')
				}
			})
			.catch(err => {
				res.redirect('/error?message=' + err.message)
			})
		} else {
			res.redirect('/error?message=Not%20Authorized')
		}
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})
})


router.get('/addProject', (req, res) => {
	if (req.vertexSession == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	if (req.vertexSession.user == null){ // user not logged in, redirect to login page:
		res.redirect('/login')
		return
	}

	const data = { id: req.vertexSession.user.id }

	res.render('addProject', data);

})

router.get('/about', (req, res) => {
	res.render('about', null);
})

router.get('/export', (req, res) => {
	controllers.user.getById(req.vertexSession.user.id)
	.then(user => {
		if (user.role === 'instructor') {
			csv(user.major)
			.then(() => {
				res.sendFile('download.csv', { root: './'});
			})
			.catch(err => {
				res.redirect('/error?message=' + err.message)
			})
		} else if (user.role === 'admin') {
			csv('admin')
			.then(() => {
				res.sendFile('download.csv', { root: '../'});
			})
			.catch(err => {
				res.redirect('/error?message=' + err.message)
			})
		} else {
			res.redirect('/error?message=Not%20Authorized');	
		}
	})
	.catch(err => {
		res.redirect('/error?message=' + err.message)
	})	
})


// this page handles general errors. the error message is passed
// in as a query parameter with key "message" and rendered in the 
// template via Mustache templating:
router.get('/error', (req, res) => {
	res.render('error', {message: req.query.message})
})

// these are for static pages:
router.get('/:page', (req, res) => {
	const page = staticPages[req.params.page]
	if (page == null){
		res.render('error', {message: 'Page not found'})
		return
	}

	res.render(page, null)
})


module.exports = router
