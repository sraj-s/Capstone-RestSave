var nodemailer = require('nodemailer');

class MailSender {
	
	constructor() {
		this.transporter = nodemailer.createTransport({
			service: 'gmail',
			host: 'smtp.gmail.com',
			port: 465,
			secure: true,
			auth: {
				user: 'testmailnodemailer0@gmail.com',
				pass: 'ojakamkar4321'
			},
			tls: {
				rejectUnauthorized: false
			}
		});
	}
	
	sendMail(mailOptions, callback) {
		this.transporter.sendMail(mailOptions, function(error, info){
			callback(error, info);
		});
	}
	
}

module.exports = {MailSender};