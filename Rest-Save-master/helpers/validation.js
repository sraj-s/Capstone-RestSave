'use strict';
module.exports = function() {
    return {
        getLoginValidation: (req, res, next) => {

            req.checkBody('email', 'Email is Invalid').isEmail();
            req.checkBody('email', 'Email must not be empty').notEmpty();
            req.checkBody('password', 'Password must not be empty').notEmpty();
            req.checkBody('password', 'Password must be 8 or more characters longer').isLength({min : 8});           

            req.getValidationResult()
                .then((result) => {
                    
                    var errors = result.array();
                    var messages = [];
                    errors.forEach((error) => {
                        messages.push(error.msg);
                    });
                    if(messages.length > 0) {
						req.flash('errors', messages);
						res.redirect('/login');
                    }
                    else {
                        return next();
                    }
                })
                .catch((err) => {
                    throw err;
                })
                
        },

        getRegisterValidation: function(req, res, next) {
            
			
			
			req.checkBody('firstname', 'First name is required').notEmpty();
			req.checkBody('lastname', 'Last name is required').notEmpty();
			
			req.checkBody('email', 'Email is Invalid').isEmail();
            req.checkBody('email', 'Email must not be empty').notEmpty();
            
			req.checkBody('password', 'Password must not be empty').notEmpty();
            req.checkBody('password', 'Password must be 8 or more characters longer').isLength({min : 8});
            
			req.checkBody('confirm_password', 'Confirm Password must not be empty').notEmpty();
            req.checkBody('confirm_password', 'Confirm Password must be 8 or more characters longer').isLength({min : 8});
            
			req.checkBody('role', 'Role is required').notEmpty();
				
            req.getValidationResult().then( (result) => {
				
				var errors = result.array();
                var messages = [];
                errors.forEach(function(error){
                    messages.push(error.msg);
                });

                if(req.body.password != req.body.confirm_password) {
					messages.push("Password does not match");
				}
				
				if(messages.length > 0) {
					req.flash('errors', messages);
					res.redirect('/register');
				}
                else {
                    next();
                }
            }).catch( (err) => {
                if(err) 
                    throw err;
            });
        },
		
		resetPassword : function(req, res, next) {
			req.checkBody('password', 'Password must not be empty').notEmpty();
            req.checkBody('confirm_password', 'Password must not be empty').notEmpty();
			
			req.checkBody('password', 'Password must be 8 or more characters longer').isLength({min: 8});
            req.checkBody('confirm_password', 'Confirm Password must be 8 or more characters longer').notEmpty({min: 8});
            
            req.getValidationResult()
                .then((result) => {
                    
                    var errors = result.array();
                    var messages = [];
                    errors.forEach((error) => {
                        messages.push(error.msg);
                    });
					
					if(req.body.password != req.body.confirm_password) {
						messages.push("Password does not match");
					}
					
                    if(messages.length > 0) {
						res.render("reset-password", {hasErrors : true, messages : messages, user: req.body.user});
                    }
                    else {
                        next();
                    }
                })
                .catch((err) => {
                    throw err;
                })
		}

    }
}