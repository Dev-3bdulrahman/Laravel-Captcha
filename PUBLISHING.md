# Publishing Laravel Captcha to Packagist

This guide will help you publish the Laravel Captcha package to Packagist.

## Prerequisites

Before publishing, ensure you have:

- ✅ GitHub account
- ✅ Packagist account (https://packagist.org)
- ✅ Git installed locally
- ✅ All tests passing
- ✅ Documentation complete

## Step 1: Prepare the Repository

### 1.1 Initialize Git Repository

```bash
cd /path/to/Laravel-Captcha
git init
git add .
git commit -m "Initial commit: Laravel Captcha v1.0.0"
```

### 1.2 Create GitHub Repository

1. Go to https://github.com/new
2. Repository name: `Laravel-Captcha`
3. Description: "A simple, standalone Laravel CAPTCHA package with multiple styles and difficulty levels"
4. Make it public
5. Don't initialize with README (we already have one)
6. Click "Create repository"

### 1.3 Push to GitHub

```bash
git remote add origin https://github.com/Dev-3bdulrahman/Laravel-Captcha.git
git branch -M main
git push -u origin main
```

## Step 2: Create a Release Tag

### 2.1 Tag the Release

```bash
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### 2.2 Create GitHub Release

1. Go to your repository on GitHub
2. Click "Releases" → "Create a new release"
3. Choose tag: `v1.0.0`
4. Release title: `v1.0.0 - Initial Release`
5. Description:
```markdown
## 🎉 Initial Release

Laravel Captcha v1.0.0 is now available!

### Features
- 🎨 4 Captcha Types (Image, Math, Text, Slider)
- 🎯 3 Difficulty Levels (Easy, Medium, Hard)
- 🎭 4 Visual Styles (Default, Modern, Minimal, Colorful)
- 🔒 Fully Standalone (No external APIs)
- 📱 Responsive Design
- ✅ Well Tested (12 tests, 26 assertions)

### Installation
```bash
composer require dev-3bdulrahman/laravel-captcha
```

### Quick Start
See [QUICKSTART.md](QUICKSTART.md) for a 5-minute setup guide.

### Documentation
- [README.md](README.md) - Full documentation
- [INSTALLATION.md](INSTALLATION.md) - Installation guide
- [Examples](examples/) - Usage examples

Made with ❤️ by [Abdulrahman Mehesan](https://3bdulrahman.com/)
```
6. Click "Publish release"

## Step 3: Submit to Packagist

### 3.1 Login to Packagist

1. Go to https://packagist.org
2. Login with your account (or create one)
3. Connect your GitHub account if not already connected

### 3.2 Submit Package

1. Click "Submit" in the top menu
2. Enter repository URL: `https://github.com/Dev-3bdulrahman/Laravel-Captcha`
3. Click "Check"
4. Review the information
5. Click "Submit"

### 3.3 Enable Auto-Update

1. Go to your package page on Packagist
2. Click "Settings"
3. Copy the webhook URL
4. Go to your GitHub repository settings
5. Click "Webhooks" → "Add webhook"
6. Paste the Packagist webhook URL
7. Content type: `application/json`
8. Select "Just the push event"
9. Click "Add webhook"

## Step 4: Verify Installation

Test that your package can be installed:

```bash
# Create a new Laravel project
composer create-project laravel/laravel test-app
cd test-app

# Install your package
composer require dev-3bdulrahman/laravel-captcha

# Publish assets
php artisan vendor:publish --tag=captcha-config
php artisan vendor:publish --tag=captcha-assets
```

## Step 5: Add Badges to README

Update your README.md with badges:

```markdown
[![Latest Version on Packagist](https://img.shields.io/packagist/v/dev-3bdulrahman/laravel-captcha.svg?style=flat-square)](https://packagist.org/packages/dev-3bdulrahman/laravel-captcha)
[![Total Downloads](https://img.shields.io/packagist/dt/dev-3bdulrahman/laravel-captcha.svg?style=flat-square)](https://packagist.org/packages/dev-3bdulrahman/laravel-captcha)
[![License](https://img.shields.io/packagist/l/dev-3bdulrahman/laravel-captcha.svg?style=flat-square)](https://packagist.org/packages/dev-3bdulrahman/laravel-captcha)
[![Tests](https://github.com/Dev-3bdulrahman/Laravel-Captcha/workflows/Tests/badge.svg)](https://github.com/Dev-3bdulrahman/Laravel-Captcha/actions)
```

## Step 6: Promote Your Package

### 6.1 Social Media

Share on:
- Twitter/X
- LinkedIn
- Reddit (r/laravel, r/PHP)
- Dev.to
- Medium

Example post:
```
🎉 Just released Laravel Captcha v1.0.0!

A simple, standalone CAPTCHA package for Laravel with:
✅ 4 captcha types
✅ 3 difficulty levels
✅ 4 visual styles
✅ No external APIs needed

Check it out: https://github.com/Dev-3bdulrahman/Laravel-Captcha

#Laravel #PHP #OpenSource
```

### 6.2 Laravel News

Submit to Laravel News:
- https://laravel-news.com/submit

### 6.3 Awesome Laravel

Submit a PR to add your package:
- https://github.com/chiraggude/awesome-laravel

### 6.4 Laravel Packages

Add to:
- https://laravelpackages.com

## Step 7: Maintain the Package

### 7.1 Monitor Issues

- Respond to issues promptly
- Label issues appropriately
- Close resolved issues

### 7.2 Review Pull Requests

- Review code quality
- Run tests
- Merge or request changes

### 7.3 Release Updates

When releasing updates:

```bash
# Update version in composer.json
# Update CHANGELOG.md
git add .
git commit -m "Release v1.1.0"
git tag -a v1.1.0 -m "Release version 1.1.0"
git push origin main
git push origin v1.1.0
```

### 7.4 Semantic Versioning

Follow semantic versioning (semver):
- **MAJOR** (1.0.0): Breaking changes
- **MINOR** (1.1.0): New features, backward compatible
- **PATCH** (1.0.1): Bug fixes, backward compatible

## Checklist Before Publishing

- [ ] All tests passing
- [ ] Documentation complete
- [ ] README.md has clear examples
- [ ] CHANGELOG.md updated
- [ ] composer.json has correct information
- [ ] LICENSE file included
- [ ] .gitignore configured
- [ ] GitHub repository created
- [ ] Release tag created
- [ ] GitHub release published
- [ ] Submitted to Packagist
- [ ] Auto-update webhook configured
- [ ] Installation tested
- [ ] Badges added to README

## Common Issues

### Issue: Package not found

**Solution:** Wait a few minutes for Packagist to index your package.

### Issue: Webhook not working

**Solution:** 
1. Check webhook URL is correct
2. Ensure repository is public
3. Test webhook delivery in GitHub settings

### Issue: Composer can't find version

**Solution:** Make sure you've created and pushed a git tag.

## Support After Publishing

### Documentation
- Keep README.md updated
- Add more examples
- Create video tutorials

### Community
- Answer questions in issues
- Help users in discussions
- Accept contributions

### Marketing
- Write blog posts
- Create tutorials
- Share updates

## Resources

- **Packagist:** https://packagist.org
- **Composer Documentation:** https://getcomposer.org/doc/
- **Semantic Versioning:** https://semver.org
- **GitHub Releases:** https://docs.github.com/en/repositories/releasing-projects-on-github

## Contact

If you need help publishing:
- Email: dev.3bdulrahman@gmail.com
- GitHub: @Dev-3bdulrahman
- Website: https://3bdulrahman.com/

---

Good luck with your package! 🚀

**Made with ❤️ by Abdulrahman Mehesan**

