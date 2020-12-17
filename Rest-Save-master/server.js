// Required Dependencies

const express = require('express');
const path = require('path');
const mongoose = require('mongoose');
const http = require('http');
const session = require('express-session');
const flash = require('connect-flash');
const MongoStore = require('connect-mongo')(session);
const ejs = require('ejs');
const bodyParser = require('body-parser');
const cookieParser = require('cookie-parser');
const passport = require('passport');
const validator = require('express-validator');

// Container

const container = require('./container');

// Implementing Main App

container.resolve(function (_, user, post) {

    mongoose.Promise = global.Promise;
    mongoose.connect("mongodb+srv://shehroz:shehroz@cluster0.sylbf.mongodb.net/restsave?retryWrites=true&w=majority", { useNewUrlParser: true, useUnifiedTopology: true });

    var app = initializeApp();

	// Setting up the port to listen request on
    function initializeApp() {

        var app = express();
        var server = http.createServer();
        var port_number = server.listen(process.env.PORT || 3000);
        app.listen(port_number);

        configureApp(app);
    }

	// Configure App Middlewares
    function configureApp(app) {

        // For Implementing Login and Signup Process (Including Session Handling)
		require('./passports/passport-local');
        
		// Setting up the public directory to store css, js and images file.
        app.use(express.static(path.join(__dirname, "/public")));
        app.use("/*/assets", express.static(path.join(__dirname, "/public/assets")));
        
		// Setting up the templating engine
		app.set('view engine', 'ejs');
        
		
		// For fetching data from client side
        app.use(bodyParser());
        app.use(bodyParser.urlencoded({ extended: false }));
		app.use(bodyParser.json());
		app.use(cookieParser());

		// For validating data
        app.use(validator());

		// Session setup
        app.use(session({
            secret: 'addyourownsecret',
            saveUninitialized: true,
            resave: true,
            store: new MongoStore({ mongooseConnection: mongoose.connection })
        }));

		// For showing alerts
        app.use(flash());
        
		// Passport Setup
        app.use(passport.initialize());
        app.use(passport.session());

        
        // App Routing 
        var router = require('express-promise-router')();
        
		// Routes
        user.setRouting(router);
		post.setRouting(router);
		app.use(router);

		// Make lodash work on client side too
        app.locals._ = _;

    }

});