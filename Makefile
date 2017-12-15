all: install test lint check-style

install:
	composer install --prefer-dist

test:
	./vendor/bin/phpunit

test-with-coverage:
	./vendor/bin/phpunit --coverage-text --coverage-html=report/

test-with-coverage-clover:
	./vendor/bin/phpunit --coverage-text --coverage-clover=coverage.xml

lint:
	# Lint all PHP files in parallel (across 8 threads)
	find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -n 1 -0 -P 8 php -l 1>/dev/null

check-style:
	./vendor/bin/phpcs --standard=phpcs.xml.dist --encoding=utf-8 -p
