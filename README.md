This is a basic test task implementation.

It fetches coin rates every 15 seconds into the local DB and implements very simple API that provides the accumulated data.


**To get is running in the local test environment:**


git clone:
```
git clone https://github.com/Arsenitim/simple-test-symfony
```

dependencies:
```
composer install
```

configure db and sentry (.env):
```
DATABASE_URL=...
SENTRY_DSN=...
```

run migrations:
```
php bin/console doctrine:migrations:migrate
```

fixtures (adds five test coins/currencies):
```
php bin/console doctrine:fixtures:load
```

start server:
```
symfony server:start
```


enable scheduler (data collection interval is **hard coded** to 15 seconds):
```
symfony console messenger:consume -v scheduler_default
```

test the endpoint after a couple of minutes. It should have some data:

http://127.0.0.1:8000/api/currency_pairs

http://127.0.0.1:8000/api/chart_data?currencyBase=bitcoin&currencyQuote=usd&beginDateTimeStr=2000-01-12&endDateTimeStr=2050-01-12  

http://127.0.0.1:8000/api/chart_data?currencyBase=bitcoin&currencyQuote=usd


**Testing**

prepare the test db:
```
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
php bin/console doctrine:migrations:migrate --env=test
```

run tests:
```
php bin/phpunit
```
