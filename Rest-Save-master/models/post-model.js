const mongoose = require('mongoose');
const fs = require('fs');
const path = require('path');

const PostSchema = mongoose.Schema({
	
	restaurant_name: {type: String, default: '', required: true},
	food_description: {type: String, default: '', required: true},
	number_of_persons: {type: Number, default: 0, required: true},
	location: {
		longitude: {
			type: String, default: ''
		},
		latitude: {
			type: String, default: ''
		}
	},
	image: {type: String, default: '', required: true},
	claimed: {type: Boolean, default: false},
	createdBy : {type: mongoose.Schema.Types.ObjectId, ref: 'User'},
	createdAt : {type : Date, default : Date.now},
    
});

PostSchema.methods.deleteCurrentImage = function() {
	let post = this;
	
	try {
		fs.unlink( path.join(__dirname, "../public" + post.image), (err) => {
			if(err) {
				return console.log(err);
			}
			return;
		});
	} catch(err) {
		console.log(err);
	}
}

module.exports = mongoose.model('Post', PostSchema);