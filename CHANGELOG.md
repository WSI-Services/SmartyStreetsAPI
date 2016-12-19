# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

<!--
Notes: http://keepachangelog.com/
- Version Release Sections title "[Z.Y.X] - YYYY-MM-DD"
- Sub-sections
-- Added - New features.
-- Changed - Changes in existing functionality.
-- Deprecated - Once-stable features removed in upcoming releases.
-- Removed - Deprecated features removed in this release.
-- Fixed - Any bug fixes.
-- Security - Invite users to upgrade in case of vulnerabilities.
 -->
## [Unreleased]
### Changed
- Correct SmartyStreets URLs
- Laravel install instructions
- Updated tests to reflect API changes

### Fixed
- Updated API endpoints
- TravisCI configuration
- Correct testing service credentials


## [1.0.0] - 2016-03-12
### Added
- US Street Address service handler.
- Request classes - FileGetContents, cURL, PeclHttp, GuzzleHttp.
- Laravel support with service provider and facade, for integration into Laravel projects.
- Development tools - phpUnit, phploc, phpDocumentor.
- Travis CI configuration file & run script.
- Vagrant development environment.

[Unreleased]: https://github.com/WSI-Services/SmartyStreetsAPI/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/WSI-Services/SmartyStreetsAPI/compare/v0.0.1...v1.0.0
