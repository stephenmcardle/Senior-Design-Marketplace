# Senior Design Marketplace Documentation

The Senior Design Marketplace is a Turbo 360-based Node/Express project with a Turbo 360 database, based on MongoDB. For more information on Turbo 360, see [here](https://turbo360.co).

## Getting Started

Ensure you have Node version 6 or newer. Run the following commands:

```
$ sudo npm i -g gulp
$ sudo npm i -g webpack
$ sudo npm i -g turbo-cli
```

Update: Towards the end of the 2018 Spring semester, Turbo was updated to use Node 8.x. This means the project can now be updated to use async/await, as well as other features.

## File Structure

### marketplace

This is the root directory of the project. 

`app.js` is the main entry point for the project. This file imports the routes.

`package.json` contains information about the project, NPM scripts, and dependencies.

`package-lock.json` is generated by NPM and in general should not be changed.

`.env` contains global environment variables required for the application to run on Turbo. Particularly important is **TURBO_APP_ID**, which specifies where local source code should be deployed to.

`DOCUMENTATION.md` and `README.md` are shipped with every Turbo project, and contain information about running the project and general Turbo documentation.

`SD-DOCUMENTATION.md` contains information about running this project as well as the structure of its source code.

#### public

This directory contains the public assets that are used to display the front end in a browser. Among these assets are css, fonts, images, and js.

#### views

This directory contains the 'HTML' code for the project. The project uses the mustache templating engine to render the front end. To render dynamic data with mustache, double curly brace syntax is used, like so: {{user.username}}

The file names directly correspond to the content they show. Note that the difference between 'view all' and 'view one' is specified by whether the file name is plural, such as `projects.mustache` vs. `project.mustache`.

##### partials

Partials allow us to include snippets of code throughout our page and help us to avoid repetition. They can be included in another file like so: {{>head}}

#### routes

These are standard Node/Express routes. When a user hits a route (by specifying URL, or clicking on something that links to another URL), these files tell the server what actions to take and what data to serve.

`index.js` is the main route page for the project. It serves all of the dynamic content to the front end. Most changes to the project's routes will be done here.

`auth.js` is used for user authentication. It handles login, registration, logout, updating, and getting the current user.

`api.js` is a general API route file used for a RESTful API.

#### controllers

Controllers connect the server to the Turbo datastore. They work very similarly to MongoDB database functions. The title of the controller specifies what entity it is used for. If one wanted to add a new database function, such as getProjectCreatedBefore(date), it would be done in ProjectController.js. The general functionality provided by controllers is get (fetch), post (create), put (update), and delete (remove).

## Running the Project

If you haven't logged in to Turbo locally (via terminal), run the following command:

```
$ turbo login
```

And enter the same credentials you used to create your account on Turbo 360's website. Note that in order to access the Turbo Datastore, you must be added to the project as a collaborator on the Turbo website.

To test the project locally, run the following command:

```
$ turbo devserver
```

Then navigate to **localhost:3000** in your browser. The local version of the project connects to the same database as the live version, so keep this in mind when testing.

To deploy the project to its staging (live, but not production) environment, run the following command:

```
$ turbo deploy
```

Deployment can take up to a few minutes. When deployment is complete, you will see something like:

```
DEPLOY COMPLETE: http://YOUR_STAGING_URL.turbo360-vertex.com
```

The given URL is the staging link for the Turbo project.
