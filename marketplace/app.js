const vertex = require('vertex360')({site_id: process.env.TURBO_APP_ID})

// initialize app
const app = vertex.app()

// import routes
const index = require('./routes/index')
const api = require('./routes/api')
const auth = require('./routes/auth')

// set routes
app.use('/', index)
app.use('/api', api)
app.use('/auth', auth)


module.exports = app