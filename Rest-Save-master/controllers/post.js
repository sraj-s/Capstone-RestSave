'use strict';
// Required Dependencies

var mv = require('mv');
var fs = require('fs');
var path = require('path');
var multer = require('multer');


// Multer Middleware for uploading files
const storage = multer.diskStorage({
	destination(req, file, cb) {
		cb(null, path.join(__dirname, '../public/uploads/'));
	},
	filename(req, file, cb) {
		const ext = path.extname(file.originalname).toLowerCase();
		var datetimestamp = Date.now();
		var file_name = file.fieldname + '-' + datetimestamp + '.' + file.originalname.split('.')[file.originalname.split('.').length -1];
		cb(null, file_name);
	},
});

const upload = multer({
	storage,
	limits: { fileSize: 3000000 },
	fileFilter(req, file, cb) {
		const ext = path.extname(file.originalname).toLowerCase();
		if (ext !== ".png" && ext !== ".jpg" && ext !== ".jpeg") {
			cb(new Error("Error: Unacceptable file format"), false);
		} else {
			cb(null, true);
		}
	},
}).single('filetoupload');




module.exports = function(Post) {
	return {
		// Defining routes
	
		setRouting : function(router) {
			router.post('/post/food', this.addPost);
			router.post('/post/delete', this.deletePost);
			router.post('/post/getById', this.getPostById);
			router.get('/post/update', this.updatePostView);
			router.post('/post/food/update', this.updatePost);
			router.post('/post/claim', this.claimFood);
		},
	
		// Route to add a new Post
		addPost : function(req, res) {
			upload(req, res, async err => {
				if(err) {
					res.render("post.ejs", {hasError: true});
				}
				else {
					let post = new Post();
					post.restaurant_name = req.body.restaurant_name;
					post.food_description = req.body.food_description;
					post.number_of_persons = req.body.number_of_persons;
					post.image = "/uploads/" + req.file.filename; 
					
					let coordinates = JSON.parse(req.body.address);
					post.location.longitude = coordinates.longitude;
					post.location.latitude = coordinates.latitude;
					
					post.createdBy = req.user;
			
					post.save().then(function(savedPost) {
						res.render("post.ejs", {hasError: false});
					}).catch(function(err) {
						res.render("post.ejs", {hasError: true});
					});	
				}
			})
		},
		
		// Route to delete a new Post
		deletePost : async function(req, res) {
			let post = await Post.findOne({ _id: req.body.post_id });
			post.deleteCurrentImage();
			post.deleteOne().then( () => { 
				return res.json({error: false}); 
			}).catch( (err) => { 
				return res.json({error: true, message: "Could not delete the post"}); 
			});
		},

		// Route to get a Post by its id
		getPostById: async function(req, res) {
			let post = await Post.findOne({ _id: req.body.post_id });
			if(post) {
				return res.json({error: false, post: post});
			}
			
			return res.json({error: true, error_message: "Could not fetch the post"});
		},
		
		// Route to update an existing Post
		updatePostView : async function(req, res) {
			let posts = await req.user.getPosts();
			res.render("update", {user: req.user, posts: posts});
		},
		
		updatePost : async function(req, res) {
			upload(req, res, async err => {
		
				if(err) {
					res.redirect("/post/update");
				}
				
				else {
					let post = await Post.findOne({ _id: req.body.post_id });
					
					if(req.file) {
						post.deleteCurrentImage();
						post.image = "/uploads/" + req.file.filename; 
					}
					
					post.restaurant_name = req.body.restaurant_name;
					post.food_description = req.body.food_description;
					post.number_of_persons = req.body.number_of_persons;
					
					await post.save();
					return res.redirect('/post/update');
				}
			})
		},
		
		// Route to make an unclaimed food claimed
		claimFood : async function(req, res) {
			let post = await Post.findOne({ _id: req.body.post_id });
			if(post) {
				post.claimed = true;
				await post.save();
				return res.json({error: false});
			}
			
			return res.json({error: true});
		}
		
    }
}
