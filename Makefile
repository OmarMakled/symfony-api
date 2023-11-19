.PHONY: install
install:
	@docker-compose exec php composer install
	@docker-compose exec php ./bin/console doctrine:database:create --if-not-exists
	@docker-compose exec php ./bin/console doctrine:schema:update  --force
	@make dev
	@make jwt

.PHONE: dev
dev:
	@docker-compose exec php chmod 777 /var/www/api/public/uploads/
	@docker-compose exec php /etc/init.d/cron start

.PHONE: jwt
jwt:
	@docker-compose exec php openssl genpkey -algorithm RSA -out config/jwt/private.pem
	@docker-compose exec php openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
	@docker-compose exec php chmod 644 config/jwt/public.pem config/jwt/private.pem

.PHONY: test
test:
	@docker-compose exec php ./bin/console doctrine:database:create --if-not-exists --env test
	@docker-compose exec php ./bin/console doctrine:schema:update  --force --env test
	@docker-compose exec php ./bin/phpunit --coverage-html=coverage

.PHONY: autofix
autofix:
	@docker-compose exec php composer autofix
	@docker-compose exec vue npm run autofix
	@docker-compose exec react npm run autofix

.PHONY: up
up:
	@docker-compose up -d

.PHONY: stop
stop:
	@docker-compose stop

.PHONY: cron
cron:
	@docker-compose exec php /bin/bash -c 'echo "*/1 * * * * /usr/local/bin/php /var/www/api/bin/console app:send-activation-emails" | crontab -'
	@echo "Cron job installed successfully."

.PHONY: remove-cron
remove-cron:
	@docker-compose exec php crontab -r
	@echo "Cron job removed successfully."

.PHONY: vue
vue:
	@docker-compose exec vue npm install
	@docker-compose exec vue npm run dev

.PHONY: react
react:
	@docker-compose exec react npm install
	@docker-compose exec react npm start
