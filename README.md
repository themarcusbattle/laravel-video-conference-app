This repo will allow you to create your own Laravel 7 video chat room. 

If you're interested in actually creating this from scratch, a tutorial is available at twilio.com.

## Installation

To install on your computer, run the following command in your terminal:

```bash
$ git clone git@github.com:themarcusbattle/laravel-video-conference-app.git
```

Install all dependencies:

```bash
$ cd laravel-video-conference-app && npm install && composer install
```

Create a new `.env` file.

```bash
$ cp .env.example .env
```

Update the database credentials and generate a new application key.

```bash
$ php artisan key:generate
```

Add your Twilio Credentials to the `.env` file. If you do not have a Twilio account, you can [create a free one and receive $10 in credit](http://www.twilio.com/referral/5PFGwv).

You’ll need to copy the Account SID from your [account dashboard](https://www.twilio.com/console). An API Key and API Secret will need to be created from your [API Keys list](https://www.twilio.com/console/video/project/api-keys/create).

```
TWILIO_ACCOUNT_SID="Insert your Account SID"
TWILIO_API_KEY_SID="Insert your API Key"
TWILIO_API_KEY_SECRET="Insert your API Secret"
```

Lastly, clear the Laravel cache to ensure that your dotenv variable are loaded:

```bash
$ php artisan config:clear
```

Start the application using the Laravel artisan command:

```bash
$ php artisan serve
```

Visit `http://127.0.0.1:8000/` and test things out!

**NOTE:** You will only be able to use this in production under https.

## Push to Production
If you would like to host this app on your own server, I highly recommend [Amezmo](https://www.amezmo.com/r/74956eaad08d5bc2), a dedicated PHP platform with automatic deployments. Fork this repo and deploy!