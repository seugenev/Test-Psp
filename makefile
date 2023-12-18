ifeq (artisan,$(firstword $(MAKECMDGOALS)))
  RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(RUN_ARGS):;@:)
endif

ifeq (composer,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "composer"
  RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(RUN_ARGS):;@:)
endif

supervisor restart:
	docker-config/supervisor restart all

artisan:
	docker-config/artisan $(RUN_ARGS)

composer:
	docker-config/composer $(RUN_ARGS)

migrate:
	docker-config/artisan migrate

seed:
	docker-config/artisan db:seed
	docker-config/artisan module:seed

cache:
	docker-config/artisan optimize:clear

install:
	docker-config/composer install

passport:
	docker-config/artisan passport:install

up:
	docker-compose up --build --detach

down:
	docker-compose down

re: down up

refresh:
	docker exec zick-docker-mysql mysqldump -u root -pQNs4xxxt7LBFY6ymt6g2Ktgaf2BKnx zick_db oauth_access_tokens oauth_auth_codes oauth_clients oauth_personal_access_clients oauth_refresh_tokens users > ./mysql-data/temp.sql
	docker-config/artisan migrate:fresh
	docker-config/artisan db:seed
	docker exec -i zick-docker-mysql mysql -u root -pQNs4xxxt7LBFY6ymt6g2Ktgaf2BKnx -e "use zick_db; SET foreign_key_checks = 0; DROP TABLE IF EXISTS oauth_access_tokens, oauth_auth_codes, oauth_clients, oauth_personal_access_clients, oauth_refresh_tokens, users; SET foreign_key_checks = 1;"
	docker exec -i zick-docker-mysql mysql -u root -pQNs4xxxt7LBFY6ymt6g2Ktgaf2BKnx zick_db < ./mysql-data/temp.sql
	rm -f ./mysql-data/temp.sql

logs:
	tail -f ./application/storage/logs/laravel-$(shell date +%Y-%m-%d).log

logs-enroll:
	tail -f ./application/storage/logs/enrollment-flow-$(shell date +%Y-%m-%d).log

logs-send:
	tail -f ./application/storage/logs/send-flow-$(shell date +%Y-%m-%d).log

logs-receive:
	tail -f ./application/storage/logs/receive-flow-$(shell date +%Y-%m-%d).log

docs:
	docker-config/artisan scribe:generate

hooks:
	git config --local include.path ../.gitconfig

test:
	docker-config/artisan cache:clear
	docker-config/artisan migrate:fresh --env'=testing'
	docker-config/artisan db:seed --env'=testing'
	docker-config/artisan module:seed --env'=testing'
	docker-config/artisan test
