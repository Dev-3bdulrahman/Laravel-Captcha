# Changelog

All notable changes to `Laravel Captcha` will be documented in this file.

## [1.0.0] - 2025-10-01

### Added
- Initial release
- Image captcha with GD library
- Math captcha with multiple operators
- Text captcha with customizable questions
- Slider captcha with puzzle verification
- Three difficulty levels (easy, medium, hard)
- Four visual styles (default, modern, minimal, colorful)
- Blade components for easy integration
- Custom validation rule
- Session-based captcha storage
- Automatic expiration
- Refresh functionality
- Responsive design
- Comprehensive documentation
- Unit tests
- Demo examples

### Features
- ✅ Fully standalone (no external dependencies)
- ✅ Multiple captcha types
- ✅ Customizable difficulty levels
- ✅ Multiple visual styles
- ✅ Easy Laravel integration
- ✅ Responsive and mobile-friendly
- ✅ Comprehensive configuration options
- ✅ Well-tested codebase

## [2.0.0] - 2025-01-15

### Added
- 🎨 **SVG Captcha Support**: Generate captcha images in SVG format without requiring GD Library
- 🚀 **No GD Dependency**: Use SVG captcha as an alternative to PNG format
- ⚙️ **New Configuration Option**: `use_svg` setting in image captcha configuration
- 🔧 **Environment Variable**: `CAPTCHA_USE_SVG` for easy SVG enabling
- 🛣️ **New Route**: `/captcha/svg/{type?}` for SVG captcha generation
- 📝 **Enhanced Documentation**: Updated README and installation guide for SVG usage
- 🧪 **SVG Tests**: Comprehensive test suite for SVG functionality
- 📖 **SVG Examples**: Complete example showing SVG captcha implementation

### Changed
- 📋 **Requirements**: GD Library is now optional (only required for PNG format)
- 🔄 **Backward Compatibility**: Existing PNG captcha continues to work unchanged
- 📚 **Documentation**: Updated all documentation to reflect SVG support

### Benefits of SVG Captcha
- ✅ No GD Library dependency
- ✅ Scalable vector graphics
- ✅ Smaller file size
- ✅ Better performance
- ✅ Works on all modern browsers
- ✅ Easier to style with CSS
- ✅ Accessible and SEO-friendly

## [Unreleased]

### Planned Features
- Audio captcha for accessibility
- Custom font support for SVG
- More visual effects
- Rate limiting integration
- Analytics and statistics
- Multi-language support
- Dark mode support

