const mongoose = require('mongoose');
const bcrypt =  require('bcrypt');
const crypto = require('crypto');
var Post = require('./post-model');

const UserSchema = mongoose.Schema({
	role: {type: String, default: 'restaurant'},
	
	firstname : {type : String, default : ''},
    lastname : {type : String, default : ''},  
	email : {type : String, unique : true, default:'', required: true},
    password : {type : String, default : ''},
	
	resetPasswordToken: {type: String, required: false},
	resetPasswordExpires: {type: Date, required: false}

}, {timestamps: true});

UserSchema.methods.encryptPassword = function(password) {
    return bcrypt.hashSync(password, bcrypt.genSaltSync(10), null);
}

UserSchema.methods.comparePassword = function(password) {
    return bcrypt.compareSync(password, this.password);
}

UserSchema.methods.generatePasswordReset = function() {
    this.resetPasswordToken = crypto.randomBytes(20).toString('hex');
    this.resetPasswordExpires = Date.now() + 3600000; //expires in an hour
};

UserSchema.methods.getPosts = function() {
	let user = this;
	
	if(user.role == 'restaurant') {
		return Post.find({createdBy: user._id});
	}
	
	return Post.find({});
}

module.exports = mongoose.model('User', UserSchema);