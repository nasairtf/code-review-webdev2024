# Project Structure

This document outlines the project's directory structure and intended usage. Refer to the tree view for detailed file listings.

## Philosophy
The project is organized around the MVC pattern with SRP and DRY principles. Legacy components are maintained in `legacy/` during the refactor process.

## Top-Level Directories

### `classes/`
- **Purpose**: Contains PHP classes organized by functionality. Major categories include:
  - `controllers/`: Handles incoming requests and application logic.
  - `core/`: Shared utilities and foundational logic.
    - `common/`: Helpers used across the codebase.
    - `htmlbuilder/`: Helpers for generating HTML.
    - `irtf/`: Helpers containing IRTF-specific logic.
  - `models/`: Encapsulates database interactions.
  - `schedule/`: Provides domain-level handling of schedule logic.
  - `services/`: Provides business logic and utilities (e.g., database, email, file handling).
  - `validators/`: Implements input validation, typically at the form level.
  - `legacy/`: Wrapper classes bridging refactored and legacy code.

### `configs/`
- **Purpose**: Configuration files (excluded from version control).
- **Guidelines**:
  - Sensitive data (e.g., database credentials) must not be included in the repo.
  - Use environment-specific `.env` files for overrides.

### `data/`
- **Purpose**: Uploaded or generated data files (excluded from version control).
- **Guidelines**:
  - Use subdirectories to reduce clutter.
  - Group associated subdirectories under a project (e.g. `data/schedule/cvs`).

### `public_html/`
- **Purpose**: Public-facing directory containing entry points and assets.
- **Guidelines**:
  - PHP files here should strictly act as controllers that bootstrap the application.
  - Avoid placing business logic or reusable code here.

### `tests/`
- **Purpose**: Contains PHPUnit test cases for all refactored components.

### Excluded Files/Directories
- Configuration files and other sensitive data are excluded for security.
  - See `.gitignore` for more details.

## Future Plans
- **Deprecation of `legacy/`**: Once all functionality has been refactored, this directory will be removed.

## Generated Directory Tree
```
/home/webdev2024/
├── classes
│   ├── controllers
│   │   ├── feedback
│   │   ├── ishell
│   │   ├── login
│   │   └── proposals
│   ├── core
│   │   ├── common
│   │   │   ├── Config.php
│   │   │   └── Debug.php
│   │   ├── htmlbuilder
│   │   │   ├── BaseHtmlBuilder.php
│   │   │   ├── ButtonBuilder.php
│   │   │   ├── CheckboxBuilder.php
│   │   │   ├── CompositeBuilder.php
│   │   │   ├── FormElementsBuilder.php
│   │   │   ├── HtmlBuilder.php
│   │   │   ├── HtmlBuildUtility.php
│   │   │   ├── LayoutBuilder.php
│   │   │   ├── PulldownBuilder.php
│   │   │   ├── RadioBuilder.php
│   │   │   ├── TableBuilder.php
│   │   │   ├── TableLayoutBuilder.php
│   │   │   └── TextBuilder.php
│   │   └── irtf
│   │       ├── IrtfLinks.php
│   │       └── IrtfUtilities.php
│   ├── exceptions
│   │   ├── DatabaseException.php
│   │   ├── EmailException.php
│   │   └── ValidationException.php
│   ├── legacy
│   │   ├── Applications.php
│   │   ├── FormManager.php
│   │   ├── IRTFLayout.php
│   │   └── Proposals.php
│   ├── models
│   │   ├── feedback
│   │   ├── ishell
│   │   ├── login
│   │   └── proposals
│   ├── schedule
│   │   ├── build
│   │   ├── common
│   │   ├── upload
│   │   └── ScheduleManager.php
│   ├── services
│   │   ├── database
│   │   │   ├── feedback
│   │   │   ├── ishell
│   │   │   ├── troublelog
│   │   │   ├── DatabaseService.php
│   │   │   ├── DB.php
│   │   │   └── DbQueryUtility.php
│   │   ├── email
│   │   │   ├── feedback
│   │   │   └── EmailService.php
│   │   ├── files
│   │   │   ├── FileParser.php
│   │   │   └── FileWriter.php
│   │   └── graphs
│   │       └── GraphService.php
│   ├── validators
│   │   ├── forms
│   │   │   ├── feedback
│   │   │   ├── login
│   │   │   ├── proposals
│   │   │   └── BaseFormValidator.php
│   │   └── ishell
│   └── views
│       ├── forms
│       │   ├── feedback
│       │   ├── login
│       │   ├── proposals
│       │   └── BaseFormView.php
│       └── ishell
├── configs
│   ├── contact_config.php
│   ├── db_config.php
│   ├── debug_config.php
│   ├── feedback_config.php
│   ├── ishelltemps_config.php
│   ├── login_config.php
│   └── smtp_config.php
├── public_html
│   ├── feedback
│   ├── ishell
│   ├── proposals
│   ├── Login.php
│   └── Logout.php
├── bootstrap.php
├── composer.json
├── composer.lock
├── CONTRIBUTING.md
├── deploy_manifest.conf
├── phpcs.xml
├── README.md
└── STRUCTURE.md
```
---

### Revision History
| Version | Date       | Author      | Description                       |
|---------|------------|-------------|-----------------------------------|
| 1.0     | 2024-12-05 | Miranda H-O | Initial draft of structure.md     |
| 1.1     | YYYY-MM-DD | Author Name | Description of changes            |

---

**Note**: This document should be updated whenever:
- A new top-level directory is added.
- The purpose or structure of an existing directory changes significantly.
