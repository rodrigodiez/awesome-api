# Some first considerations
In order to run this project you will need:

- `docker` installed and working
- `docker-compose` installed and working

An `api_token` parameter is needed on every request to validate that the request comes from an authorised partner. This api token can be configured by setting up an `API_TOKEN` environment variable when booting the php container. Default `docker-compose` configuration comes with `123456` configured as the api token

# How to boot this project

## Clone the repository
```git clone git@github.com:rodrigodiez/awesome-api.git```

## Run the container stack
```docker-compose up```

## Test it works
```bash
$ curl --data "api_token=123456" http://localhost/payment

{"message":"amount field is mandatory","data":null}
```

# How to report a payment
```
curl --data "api_token=123456&amount=10.10&table_number=42&restaurant_location=101&reference=foo&card_type=visa" http://localhost/payment
```

# How to request a report for all locations
```
curl http://localhost/payment\?api_token\=123456
```

# How to request a report for one location
```
curl http://localhost/payment\?api_token\=123456\&restaurant_location=101
```

# How to run tests
```
docker-compose exec web bin/phpspec run
```
