all: install test lint checkstyle

install:
	composer install --prefer-dist

test:
	./vendor/bin/phpunit

lint:
	# Lint all PHP files in parallel (across 8 jobs)
	find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -n 1 -0 -P 8 php -l

checkstyle:
	./vendor/bin/phpcs --standard=PSR2 --encoding=utf-8 -p src/ tests/
