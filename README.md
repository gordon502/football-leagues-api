# football-leagues-api

## Development

### Environment variables
Copy the `.env` file to `.env.local` and fill/replace the values with your own,
especially the `MONGODB_URL`, `MONGODB_DB` and `DATABASE_URL`.

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

### Create/Update DB schemas
Inside the PHP container, run:
```bash
php bin/console doctrine:mongodb:schema:create
php bin/console doctrine:schema:create
```

If you want to update the schema, run:
```bash
php bin/console doctrine:mongodb:schema:update --force
php bin/console doctrine:schema:update --force
```
