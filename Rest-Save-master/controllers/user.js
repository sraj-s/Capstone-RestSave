'use strict';

module.exports = function(passport, validation, User, Post, email) {
  return {
	// Declaring Routes
    setRouting : function(router) {
		router.get('/', this.homePageView);
		router.get('/homepage', this.homePageView);   
			
		router.get('/login', this.loginView);
		router.post('/login', validation.getLoginValidation, this.login);
            
		router.get('/register', this.registerView);
		router.post('/register', validation.getRegisterValidation, this.register);
			
		router.get('/forgot_password', this.forgotPasswordView);
		router.post('/forgot_password', this.forgotPassword);
		router.get('/auth/reset/:token', this.verifyToken);
			
		router.post('/reset_password', validation.resetPassword, this.resetPassword);
	 	
		router.get('/logout', this.logOut);
		router.get('/404', this.errorNotFound);
    },
    
	// Route to display homepage
    homePageView : async function(req, res) {
		if(req.user) {
			let posts = await req.user.getPosts();
			let canClaimFood = (req.user.role == 'non-profit-organization') ? true : false;
			res.render("homepage", {user: req.user, posts: posts, canClaimFood: canClaimFood});
		}
		else {
			res.redirect("/login");
		}
    },
	
	// Route to display Login page
    loginView : function(req, res) {
		let messages = req.flash('errors');
		res.render("login", {hasErrors : (messages.length > 0) ? true : false, messages : messages});
    },
	
	// Route to Implement Login Process
	login : passport.authenticate('local.login', {
      successRedirect : '/homepage',
      failureRedirect : '/login',
      failureFlash : true
    }),

	// Route to display register page
    registerView : function(req, res) {
      let messages = req.flash('errors');
	  res.render('register', { hasErrors: (messages.length > 0) ? true : false, messages: messages});
    },

	// Route to Implement Register process
    register : passport.authenticate('local.signup', {
      successRedirect : '/login',
      failureRedirect : '/register',
      failureFlash : true
    }),

	// Route to display forgot-password page
    forgotPasswordView: function(req, res) {
		let messages = req.flash('errors');
		res.render("forgot-password", {hasErrors : (messages.length > 0) ? true : false,hasSuccess: false, messages : messages});
    },
	  
	// Route to implement forgot-password mechanism
    forgotPassword: function(req, res) {
		User.findOne({ email: req.body.email }).then(function(user){
			if(!user) {
				req.flash('errors', ['User with this email does not exist']);
				res.redirect('/forgot_password');
			}
			else if(user) {
				user.generatePasswordReset();
				user.save().then(function(savedUser) {
					let link = "http://" + req.headers.host + "/auth/reset/" + savedUser.resetPasswordToken;
						
					let mailSender = new email.MailSender();
					const mailOptions = {
						from: 'mohammadshehroz558@gmail.com',
						to: savedUser.email,
						subject: "Campus Links",
						text: `Hi ${user.firstname} \n Please click on the following link ${link} to reset your password. \n\n If you did not request this, please ignore this email and your password will remain unchanged.\n`
					};
						
					mailSender.sendMail(mailOptions, function(error, info) {
						if(info) {
							res.render("forgot-password", {hasErrors: false, hasSuccess: true, messages: ['Reset link sent successfully. Check your email']});
						}
						if(error) {
							res.render("forgot-password", {hasErrors: true, hasSuccess: false, messages: ['System coult not send the email. Please try again later']});
						}
					});
				});
	        }
			else {
				res.render("forgot-password", {hasErrors: true, hasSuccess: false, messages: ['Account signed up with facebook or google can not be reset!']});
			}
		})
    },
		
	// Route for verifying token
    verifyToken: function(req, res) {
		User.findOne({resetPasswordToken: req.params.token, resetPasswordExpires: {$gt: Date.now()}}).then(function(user) {
			if(!user) {
				res.redirect('/404');
			}
			else if(user) {
				res.render("reset-password", {user: user._id, hasErrors: false});
			}
			else {
			
			}
		});
    },
		
	// Route to display reset-password interface
    resetPassword: function(req, res) {
		User.findOne({_id: req.body.user}).then(function(user) {
			user.password = user.encryptPassword(req.body.password);
			user.resetPasswordToken = '';
			user.resetPasswordExpires = '';
				
			user.save().then( (savedUser) => {
				res.redirect('/login');
			});
		}).catch(function(err) {
			res.redirect('/404');
        });
    },
			  
	// Route to logout of the current session
    logOut: function (req, res) {
		req.logout();
		req.session.destroy((err) => {
			res.clearCookie('connect.sid', { path: '/' });
			res.redirect('/login');
		});
    },
	
	// View to be rendered in case any error occurs
	errorNotFound: function(req, res) {
		res.render("error-404");
	}
  }
}
