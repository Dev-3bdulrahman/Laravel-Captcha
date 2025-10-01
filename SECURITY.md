# Security Policy

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

We take the security of Laravel Captcha seriously. If you discover a security vulnerability, please follow these steps:

### 1. Do Not Open a Public Issue

Please **do not** open a public GitHub issue for security vulnerabilities.

### 2. Contact Us Privately

Send an email to: **contact@3bdulrahman.com**

Include the following information:
- Type of vulnerability
- Full paths of source file(s) related to the vulnerability
- Location of the affected source code (tag/branch/commit or direct URL)
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue, including how an attacker might exploit it

### 3. Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Depends on severity
  - Critical: Within 7 days
  - High: Within 14 days
  - Medium: Within 30 days
  - Low: Next release cycle

### 4. Disclosure Policy

- We will acknowledge your email within 48 hours
- We will provide a more detailed response within 7 days
- We will work with you to understand and resolve the issue
- We will credit you in the security advisory (unless you prefer to remain anonymous)
- We will publicly disclose the vulnerability after a fix is released

## Security Best Practices

When using Laravel Captcha:

1. **Always validate on server-side**: Never trust client-side validation alone
2. **Use HTTPS**: Always use HTTPS in production
3. **Implement rate limiting**: Prevent brute force attacks
4. **Set appropriate expiration**: Don't make captcha valid for too long
5. **Monitor for abuse**: Keep track of failed attempts
6. **Keep package updated**: Always use the latest version
7. **Secure your session**: Use secure session configuration
8. **Use CSRF protection**: Always use Laravel's CSRF protection

## Known Security Considerations

### Session Security
- Captcha values are stored in session
- Ensure your session driver is properly configured
- Use secure session cookies in production

### Brute Force Protection
- This package does not include built-in rate limiting
- Implement rate limiting at the application level
- Consider using Laravel's built-in rate limiting features

### Image Generation
- Image captcha uses GD library
- Ensure GD library is up to date
- Be aware of potential memory issues with large images

## Security Updates

Security updates will be released as patch versions and announced via:
- GitHub Security Advisories
- Package changelog
- Email to reporters

## Bug Bounty Program

We currently do not have a bug bounty program, but we greatly appreciate responsible disclosure and will credit researchers in our security advisories.

## Questions?

If you have questions about this security policy, please contact:
- Email: contact@3bdulrahman.com
- Website: https://3bdulrahman.com/

---

Thank you for helping keep Laravel Captcha and its users safe!

