## Installation

- To start the project you need to run `docker compose up -d` it will pull the images if not exists and spin up the containers.

- Run `make install` to install dependencies if this is the first time. :dart:

## Run Tests

Run `make test` to execute all tests

![Screenshot from 2023-11-12 13-38-40](https://github.com/OmarMakled/symfony-api/assets/3720473/ed64521a-d96b-44a2-bf32-5a39de4bb676)

## Send Newsletter

Run `make cron` it runs every 1 minute for testing purposes or on demand `./bin/console app:send-activation-emails -- "-1 day"` This command has a duration as an argument the default is 1 week.

![Screenshot from 2023-11-12 13-39-41](https://github.com/OmarMakled/symfony-api/assets/3720473/dbae854d-b43e-433f-b497-934a81ca7170)

Run `make remove-cron`

## API

![Screenshot from 2023-11-12 13-38-22](https://github.com/OmarMakled/symfony-api/assets/3720473/f76f77ba-df5b-4d36-9eef-8a825b2be9e1)

## Code standard

You can check the code against the PSR-12 coding standard `make autofix`

## Stop/Start containers

Run `make stop` or `make up` to start containers again

## JWT

```
openssl genpkey -algorithm RSA -out private.pem
openssl rsa -pubout -in private.pem -out public.pem
chmod 644 public.pem private.pem
```

## Troublechute

- `/etc/init.d/cron status`
- `/var/log/dev.log`
- `http://localhost:8080/_profiler`
