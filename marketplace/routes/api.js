const turbo = require('turbo360')({site_id: process.env.TURBO_APP_ID})
const vertex = require('vertex360')({site_id: process.env.TURBO_APP_ID})
const router = vertex.router()
const controllers = require('../controllers')

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
	This is a generic API handler for a traditional REST API.
	Resources are defined in the /controllers directory and
	all CRUD operations are generally handled in the respective
	controller. 
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

router.get('/:resource', (req, res) => {
	const resource = req.params.resource
	const controller = controllers[resource] // check if valid resource

	if (controller == null){
		res.json({
			confirmation: 'fail',
			message: 'Invalid resource: ' + resource + '. Current resources: ' + Object.keys(controllers).join(', ')
		})
		return
	}

	controller.get(req.query)
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})
})

router.get('/:resource/:id', (req, res) => {
	const resource = req.params.resource
	const controller = controllers[resource] // check if valid resource

	if (controller == null){
		res.json({
			confirmation: 'fail',
			message: 'Invalid resource: ' + resource + '. Current resources: ' + Object.keys(controllers).join(', ')
		})
		return
	}

	controller.getById(req.params.id)
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})
})

// create entity
router.post('/:resource', (req, res) => {
	const resource = req.params.resource
	const controller = controllers[resource] // check if valid resource

	if (controller == null){
		res.json({
			confirmation: 'fail',
			message: 'Invalid resource: ' + resource + '. Current resources: ' + Object.keys(controllers).join(', ')
		})
		return
	}

	if (resource === 'project') {
		req.body.status = 'pending';
		req.body.accepting = 'open';
		req.body.department = 'pending'
	}

	controller.post(req.body)
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})
})

// update entity
router.put('/:resource/:id', (req, res) => {
	const resource = req.params.resource
	const controller = controllers[resource] // check if valid resource

	if (controller == null){
		res.json({
			confirmation: 'fail',
			message: 'Invalid resource: ' + resource + '. Current resources: ' + Object.keys(controllers).join(', ')
		})
		return
	}
	controller.put(req.params.id, req.body)
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})
})

router.post('/project/edit/:id', (req, res) => {	
	const resource = 'project'
	const controller = controllers[resource] // check if valid resource

	if (controller == null){
		res.json({
			confirmation: 'fail',
			message: 'Invalid resource: ' + resource + '. Current resources: ' + Object.keys(controllers).join(', ')
		})
		return
	}
	controller.put(req.params.id, req.body)
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})
})

router.post('/project/validate/:id', (req, res) => {
	const controller = controllers['project']

	controller.put(req.params.id, {status: 'approved', department: req.body.department})
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})

})

router.post('/project/deny/:id', (req, res) => {
	const controller = controllers['project']

	controller.put(req.params.id, {status: 'denied'})
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})

})

router.post('/user/validate/:id', (req, res) => {
	const controller = controllers['projectApp']
	controller.put(req.params.id, {valid: 'true'})
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})

})

router.post("/addapplication/:slug/:user",(req,res) => {
	const controller = controllers['user']
	const controller1 = controllers['project']
	const controller2 = controllers['projectApp']

	let slug=req.params.slug
	let user=req.params.user
	controller.get({username:user})
	.then(user1 => {
		controller.put(user1[0].id,{group:slug})
		.then(data => {
			controller1.get({slug:slug})
			.then(project => {
				let ans=[user]
				if(project[0].team!=null){
					ans=project[0].team
					ans.unshift(user)
				}
				controller1.put(project[0].id,{team:ans})
				.then(x => {
					controller2.get({theUserName:user,project_name:project[0].name})
					.then(theapp => {
						theapp=theapp[0]
						controller2.put(theapp.id,{valid:'true'})
						.then(data => {
							res.json({
								confirmation: 'success',
								data: data
							})
						})
						.catch(err => {
							res.json({
								confirmation: 'fail',
								message: err.message
							})
						})

					})
					.catch(x =>{
						console.log(x)
					})
				})
			.catch(err => {
				console.log(err);
			})
		})
		.catch(err => {
			console.log(err);
		})
	})
	.catch(err =>{
		console.log(err);
	})
})
.catch(err =>{
	console.log(err);
})
})
	



module.exports = router
