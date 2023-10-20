# currency-exchange-asiayo

## Setup

```
git clone git@github.com:CGW1996/currency-exchange-asiayo.git
cd currency-exchange-asiayo
docker-compose up -d --build
docker-compose exec fpm cp .env.example .env
docker-compose exec fpm composer install
docker-compose exec fpm php artisan key:generate
docker-compose exec -T fpm chown -R www-data:www-data storage
```

# API

## Get currency exchange

### Request

`GET /exchanges?source=TWD&target=JPY&amount=100.11`

    curl -X GET -H 'Accept:application/json' http://localhost:6699/api/exchanges?source=TWD&target=JPY&amount=100.11

### Response
    {"msg": "success", "amount": "367.30" }
 
# Test
```
docker-compose exec fpm php artisan test
```