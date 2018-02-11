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


module.exports = router
