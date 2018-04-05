const Promise = require('bluebird')
const controllers = require('../controllers')
const converter = require('json-2-csv').json2csvPromisified
const fs = require('fs')

const createFile = (department) => {
	return new Promise((resolve, reject) => {
		let query = {}
		if (department !== 'admin') {
			query.department = department 
		}
		controllers['project'].get(query)
		.then(data => {
			converter(data, { checkSchemaDifferences: false })
			.then(csv => {
				return new Promise((resolve, reject) => {
					fs.writeFile('download.csv', csv, function(err) {
						if (err) {
							reject(err);
						}
               			else {
               				resolve(data);
               			}
					})
				})
				
				.then(fsData => {
					resolve(fsData)
				})
				.catch(err => {
					reject(err)
				}) 
			})
			.catch(err => {
				reject(err)
			})
		})
		.catch(err => {
			reject(err)
		})
	})
}

module.exports = { createFile }

