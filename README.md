# php-starter

## Problem Title:
### Email a random XKCD challenge

## Live Link
### [https://random-comics.000webhostapp.com/](https://random-comics.000webhostapp.com/)

## Problem Statement:
Please create a simple PHP application that accepts a visitor’s email address and emails them random XKCD comics every five minutes.

- Your app should include email verification to avoid people using others’ email addresses.
- XKCD image should go as an email attachment as well as inline image content.
- You can visit https://c.xkcd.com/random/comic/ programmatically to return a random comic URL and then use JSON API for details https://xkcd.com/json.html
- Please make sure your emails contain an unsubscribe link so a user can stop getting emails.

Since this is a simple project it must be done in core PHP including API calls, recurring emails, including attachments should happen in core PHP. Please do not use any libraries.

## TODO
- [x] Input sanitization
- [x] Email verification
- [x] Storing subscription
- [x] Random selection of comics
- [x] Sending emails
- [x] Email scheduling
- [x] Unsubscribe token link
- [x] Updating README
- [x] Live demo

## Issues
- Cron job runs every 10 minutes instead of every 5 minutes due to hosting restrictions
- Hosting changing email header causing text/html rendering issues