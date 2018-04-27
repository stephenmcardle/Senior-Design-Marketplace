// This module is used for creating a CSV file when instructors and admins want to export data
const Promise = require('bluebird')
const controllers = require('../controllers')
const converter = require('json-2-csv').json2csvPromisified
const fs = require('fs')

const createFile = (department) => {
	return new Promise((resolve, reject) => {
		// Create the query
		// If the user is the admin, there is no query because we want to get all projects
		// If the user is an instructor, we query based on the instructor's department
		let query = {}
		if (department !== 'admin') {
			query.department = department 
		}
		controllers['project'].get(query) // Get projects from the database
		.then(data => {
			converter(data, { checkSchemaDifferences: false }) // Use the 'json-2-csv' converter page. In production, "checkSchemaDifferences" should be true
			.then(csv => {
				return new Promise((resolve, reject) => {
					/* 
						Turbo projects run on a read-only platform (AWS Lambda), so this cannot be used in production
						The alternative approach to this, which will work on turbo, involves
						converting the JSON project data to a CSV string on the front end,
					 	using the encodeURIComponent method,
					 	creating a new hidden HTML anchor element,
					 	setting the 'href' attribute of this element equal to the CSV string,
					 	appending the element to document.body
					 	using the .click() method to click on the anchor (which will initiate download)
					 	removing the element from document.body

					 	More info on this can be found here: https://stackoverflow.com/a/14966131
					*/
					fs.writeFile('download.csv', csv, function(err) { // Write the file
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

