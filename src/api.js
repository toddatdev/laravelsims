// @ts-nocheck

/**
 * @file api.js, root file for sending emails
 * @author Tanner Campbell
 */

/**
 * Allow Use of Dot Env File
 */
require('dotenv').config()

/**
 * NPM Packages
 */
const express = require('express');
const cron = require('node-cron'); // runs every so often
// // const schedule = require('node-schedule'); // timer executes when goes off
const mailgun = require('mailgun-js')({apiKey: process.env.MAILGUN_SECRET, domain: process.env.MAILGUN_DOMAIN});
const fetch = require('node-fetch');
// // const moment = require('moment');
// // const colors = require('colors');
// // const axios = require('axios');

/**
 * Server Variable - Allows App to run and allows use of express pkg functions
 */
const app = express();

/**
 * Set App Port Env Value, else default
 */
app.set('port', process.env.PORT || 3000);
app.use(require('body-parser').urlencoded({extended: true}));

/**
 * HTTP Header - requried by our API to have this generated key for each request 
 */
const header = {
    'Content-Type': 'application/json',
    'APP_KEY': process.env.PUSHER_APP_KEY
};

/**
 * Cron Cylce - duration every 15th minute
 */
cron.schedule('*/15 * * * *', () => {
    get()
});

/**
 * Gets Called after timed Cron goes off
 * Consumes our Laravel API
 * Might Need if running in Dev. after APP_URL - ${process.env.APP_DEV_PORT}
 * @returns {void}
 */
async function get() {
    const res = await fetch(`${process.env.APP_URL}/api/send/emails`, { headers: header });
    const emails = await res.json();
    
    emails.forEach((email) => {
        // Disabled for now
        // let job = schedule.scheduleJob(data.send_at, () => {
            send(email)
        // });     
    });    
}

/**
 * Gets called when schedulers goes off
 * Sends email via mailgun
 * @param email
 * @returns {void}
 */
function send (data) {
    let msg = {
        from: `${process.env.MAIL_FROM_NAME} <${process.env.MAIL_FROM_ADDRESS}>`,
        // TO is manditory, if no emails where given send to fallback adress
        // The 3 dots (...) are a Rest operator
        ...(data.to.length != 0 ? {to: data.to} : {to: 'tcamp022+err@gmail.com'}),
        cc: data.cc,
        bcc: data.bcc,
        subject: data.email.subject,
        html: data.email.body,
        'h:X-Header': 'AUTO SENT',
        'h:Reply-To': data.email.reply_to
    };

    // Send email via Mailgun
    mailgun.messages().send(msg, (error, response) => {
        if (error) throw error;
        console.log(response); 

        // make patch request for event email
        update(data.id);

        // make post request to the email that was just sent 
        sent(data, response);
    }); 
}


/**
 * Sent back patch request to back event email was sent
 * @param {*} id - Event Email ID
 * @returns {void}
 */
async function update(id) {
    try {
        const req = await fetch(`${process.env.APP_URL}/api/sent/email/${id}`, {
            method: 'PATCH',
            headers: header
        });
        
        // response message can be found via data
        const data = await req.json();
    } catch(err) {
        console.error('err:', err);
    }    
}


/**
 * Sends Post request to create send email message record
 * @param {*} email - info from the sent email 
 * @param {*} response - response message sent after mailgun sent it
 * @returns {void}
 */
async function sent(email, response) {
    try {
        const req = await fetch(`${process.env.APP_URL}/api/sent`, {
            method: 'POST',
            headers: header,
            body: JSON.stringify({
                email: email,
                mailgun_response: response.id
            }),
        });   

        // response message can be found via data
        const data = await req.json();                   
    } catch(err) {
        console.error('err:', err);
    }
}


/**
 * Runs The Sever
 */
app.listen(app.get('port'), () => {
    console.log(`Server listening on ${app.get('port')} press Ctrl-C to terminate`);    
});