.PHONY: install
install:
	@docker-compose exec php composer install
	@docker-compose exec php ./bin/console doctrine:database:create --if-not-exists
	@docker-compose exec php ./bin/console doctrine:schema:update  --force

.PHONY: test
test:
	@docker-compose exec php ./bin/console doctrine:database:create --if-not-exists --env test
	@docker-compose exec php ./bin/console doctrine:schema:update  --force --env test
	@docker-compose exec php ./bin/phpunit --coverage-html=coverage

.PHONY: autofix
autofix:
	@docker-compose exec php composer autofix

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