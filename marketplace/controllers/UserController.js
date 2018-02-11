const Promise = require('bluebird')
const turbo = require('turbo360')({site_id:process.env.TURBO_APP_ID})
const resource = 'user'

/* This controller manages user profiles for the Turbo project */

module.exports = {
	get: (params) => {
		return new Promise((resolve, reject) => {
			turbo.fetch(resource, params)
			.then(data => {
				resolve(data)
			})
			.catch(err => {
				reject(err)
			})
		})
	},

	getById: (id) => {
		return new Promise((resolve, reject) => {
			turbo.fetchOne(resource, id)
			.then(data => {
				resolve(data)
			})
			.catch(err => {
				reject(new Error(resource + ' ' + id + ' not found'))
			})
		})
	},

	post: (params) => {
		return new Promise((resolve, reject) => {
			turbo.create(resource, params)
			.then(data => {
				resolve(data)
			})
			.catch(err => {
				reject(err)
			})			
		})
	},

	put: (id, params) => {
		return new Promise((resolve, reject) => {
			turbo.updateEntity(resource, id, params)
			.then(data => {
				resolve(data)
			})
			.catch(err => {
				reject(err)
			})
		})
	},

	delete: (id) => {
		return new Promise((resolve, reject) => {
			
		})
	}

}

