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

	console.log('made it to put')

	if (controller == null){
		res.json({
			confirmation: 'fail',
			message: 'Invalid resource: ' + resource + '. Current resources: ' + Object.keys(controllers).join(', ')
		})
		return
	}

	console.log(req.body)

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

router.post('/editProject/:id', (req, res) => {	
	const resource = 'project'
	const controller = controllers[resource] // check if valid resource

	console.log('made it to put')

	if (controller == null){
		res.json({
			confirmation: 'fail',
			message: 'Invalid resource: ' + resource + '. Current resources: ' + Object.keys(controllers).join(', ')
		})
		return
	}

	console.log(req.body)

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

	console.log('in validate')
	console.log(req.body)
	console.log(req.params.id)
	controller.put(req.params.id, {status: 'approved', department: req.body.department})
	.then(data => {
		res.json({
			confirmation: 'success',
			data: data
		})
	})
	.catch(err => {
		console.log(err)
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
		console.log("beer2")
		res.json({
			confirmation: 'fail',
			message: err.message
		})
	})

})


module.exports = router
