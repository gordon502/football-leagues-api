# football-leagues-api

## Development

### Environment variables
Copy the `.env` file to `.env.local` and fill/replace the values with your own.

### Run the app
```bash
sh run-dev-env.sh
```

### Interactive shell with PHP and Composer
```bash
docker compose exec php bash
```

### Generate JWT keys
Inside the PHP container, run:
```bash
php bin/console lexik:jwt:generate-keypair
```
