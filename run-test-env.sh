docker compose -f docker-compose.test.yml -p football-leagues-api-test --env-file .env.test up -d
docker compose -p football-leagues-api-test exec php bash
