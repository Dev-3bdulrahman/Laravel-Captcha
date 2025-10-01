# Useful Commands for Laravel Captcha

This document contains useful commands for development, testing, and maintenance.

## Development Commands

### Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install with dev dependencies
composer install --dev

# Update dependencies
composer update
```

### Testing

```bash
# Run all tests
composer test
# or
./vendor/bin/phpunit

# Run tests with coverage
./vendor/bin/phpunit --coverage-html coverage

# Run specific test
./vendor/bin/phpunit --filter CaptchaTest

# Run tests with detailed output
./vendor/bin/phpunit --testdox

# Run tests with colors
./vendor/bin/phpunit --colors=always
```

### Code Quality

```bash
# Check PHP syntax
find src -name "*.php" -exec php -l {} \;

# Format code (if you have PHP-CS-Fixer)
vendor/bin/php-cs-fixer fix

# Static analysis (if you have PHPStan)
vendor/bin/phpstan analyse src
```

### Git Commands

```bash
# Initialize repository
git init

# Add all files
git add .

# Commit changes
git commit -m "Your message"

# Create tag
git tag -a v1.0.0 -m "Release version 1.0.0"

# Push to remote
git push origin main
git push origin v1.0.0

# View tags
git tag -l

# Delete tag
git tag -d v1.0.0
git push origin :refs/tags/v1.0.0
```

## Package Commands

### Publishing Assets

```bash
# Publish configuration
php artisan vendor:publish --tag=captcha-config

# Publish assets (CSS, JS)
php artisan vendor:publish --tag=captcha-assets

# Publish views
php artisan vendor:publish --tag=captcha-views

# Publish all
php artisan vendor:publish --provider="Dev3bdulrahman\LaravelCaptcha\CaptchaServiceProvider"

# Force publish (overwrite)
php artisan vendor:publish --tag=captcha-config --force
```

### Cache Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache
```

## File Management

### Count Files

```bash
# Count PHP files
find . -type f -name "*.php" -not -path "./vendor/*" | wc -l

# Count Blade files
find . -type f -name "*.blade.php" | wc -l

# Count all project files
find . -type f -not -path "./vendor/*" -not -path "./.git/*" | wc -l

# Count lines of code
find src -name "*.php" | xargs wc -l
```

### Search in Files

```bash
# Search for text in PHP files
grep -r "search_term" src/

# Search in all files
grep -r "search_term" .

# Search with line numbers
grep -rn "search_term" src/

# Search for class definitions
grep -r "class.*Captcha" src/
```

### File Permissions

```bash
# Make files readable
chmod -R 644 src/

# Make directories executable
find . -type d -exec chmod 755 {} \;

# Fix Laravel permissions
chmod -R 775 storage bootstrap/cache
```

## Composer Commands

### Package Information

```bash
# Show package info
composer show dev-3bdulrahman/laravel-captcha

# Show all installed packages
composer show

# Show outdated packages
composer outdated

# Validate composer.json
composer validate

# Check platform requirements
composer check-platform-reqs
```

### Autoload

```bash
# Dump autoload
composer dump-autoload

# Optimize autoload
composer dump-autoload -o

# Clear cache
composer clear-cache
```

## Documentation

### Generate Documentation

```bash
# Generate API documentation (if you have phpDocumentor)
phpdoc -d src -t docs/api

# Generate markdown from PHPDoc
vendor/bin/phpdoc-md generate src > docs/API.md
```

### View Documentation

```bash
# Serve documentation locally
cd docs && python -m http.server 8000

# Or with PHP
php -S localhost:8000 -t docs
```

## Maintenance

### Update Package

```bash
# Update version in composer.json
# Update CHANGELOG.md
# Commit changes
git add .
git commit -m "Bump version to 1.1.0"

# Create tag
git tag -a v1.1.0 -m "Release version 1.1.0"

# Push
git push origin main
git push origin v1.1.0
```

### Clean Up

```bash
# Remove vendor directory
rm -rf vendor

# Remove composer.lock
rm composer.lock

# Remove cache files
rm -rf .phpunit.result.cache

# Clean all
rm -rf vendor composer.lock .phpunit.result.cache
```

## Testing in Laravel Application

### Create Test Application

```bash
# Create new Laravel app
composer create-project laravel/laravel test-app
cd test-app

# Install package locally
composer config repositories.local path ../Laravel-Captcha
composer require dev-3bdulrahman/laravel-captcha:@dev

# Or install from Packagist
composer require dev-3bdulrahman/laravel-captcha
```

### Test Routes

```bash
# List routes
php artisan route:list | grep captcha

# Test route
curl http://localhost:8000/captcha/generate/image
```

### Start Development Server

```bash
# Start Laravel server
php artisan serve

# Start on specific port
php artisan serve --port=8080

# Start on specific host
php artisan serve --host=0.0.0.0
```

## CI/CD

### GitHub Actions

```bash
# Validate workflow
gh workflow view

# Run workflow manually
gh workflow run tests.yml

# View workflow runs
gh run list
```

## Debugging

### Enable Debug Mode

```bash
# In Laravel app
php artisan config:clear
# Set APP_DEBUG=true in .env
```

### View Logs

```bash
# Tail Laravel logs
tail -f storage/logs/laravel.log

# View last 100 lines
tail -n 100 storage/logs/laravel.log

# Search in logs
grep "error" storage/logs/laravel.log
```

### Debug Captcha

```bash
# Test captcha generation
php artisan tinker
>>> $captcha = app('captcha')->generate('image', 'medium');
>>> dd($captcha);

# Test verification
>>> $captcha = app('captcha')->generate('math', 'easy');
>>> app('captcha')->verify($captcha['value'], 'math');
```

## Performance

### Benchmark

```bash
# Time command execution
time ./vendor/bin/phpunit

# Profile with Xdebug
php -d xdebug.mode=profile vendor/bin/phpunit
```

### Optimize

```bash
# Optimize composer autoloader
composer dump-autoload -o

# Cache everything in Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Useful Aliases

Add to your `.bashrc` or `.zshrc`:

```bash
# Testing
alias pt='./vendor/bin/phpunit'
alias ptf='./vendor/bin/phpunit --filter'
alias ptd='./vendor/bin/phpunit --testdox'

# Composer
alias ci='composer install'
alias cu='composer update'
alias cda='composer dump-autoload'

# Laravel
alias pa='php artisan'
alias pam='php artisan migrate'
alias pas='php artisan serve'

# Git
alias gs='git status'
alias ga='git add .'
alias gc='git commit -m'
alias gp='git push'
```

## Quick Reference

### Most Used Commands

```bash
# Development
composer install
./vendor/bin/phpunit
php artisan serve

# Publishing
git add .
git commit -m "message"
git tag -a v1.0.0 -m "Release 1.0.0"
git push origin main --tags

# Testing in Laravel
composer require dev-3bdulrahman/laravel-captcha
php artisan vendor:publish --tag=captcha-config
php artisan vendor:publish --tag=captcha-assets
```

---

**Tip:** Save this file for quick reference during development!

