set dotenv-load := false

# default recipe to display help information
default:
  @just --list

# [DDEV] Initial project setup
setup:
	cp .env.example .env
	cd public && ln -s ../storage/app/public storage
	ddev start
	ddev composer install
	ddev exec "php artisan key:generate"
	ddev exec "php artisan migrate:fresh --seed"
	ddev exec "php artisan vendor:publish --tag=telescope-assets --force"

# [System] Set up frontend installation
frontend:
	npm i
	npm run build

# [DDEV] Run the application (after initial setup)
up:
	ddev start
	ddev launch

# [DDEV] Stop the application
down:
	ddev stop

# [DDEV] Enter webserver bash
@ssh:
	ddev ssh

# [DDEV] Lint files
@lint:
	ddev exec "./vendor/bin/ecs check --fix"
	ddev exec "./vendor/bin/php-cs-fixer fix"
	ddev exec "./vendor/bin/rector process"
	ddev exec "./vendor/bin/tlint lint"

# [DDEV] Check code quality
@quality:
	ddev exec "./vendor/bin/phpstan analyse --memory-limit=2G"

# [DDEV] Run unit and integration tests
@test:
	echo "Running unit and integration tests"; \
	ddev exec vendor/bin/phpunit

# [DDEV] Run tests and create code-coverage report
@coverage:
	echo "Running unit and integration tests"; \
	echo "Once completed, the generated code coverage report can be found under ./reports)"; \
	ddev xdebug;\
	ddev exec XDEBUG_MODE=coverage vendor/bin/phpunit;\
	ddev xdebug off;\
	xdg-open reports/index.html


# [System] Prepare branch for commit
@prepare: lint quality
	echo "All checks completed"

# [DDEV] Launch PHPMyAdmin
@db:
	ddev phpmyadmin

# [DDEV] List application commands
@list:
	ddev exec php artisan list app
