# IRTF WebDev2024 Codebase

This project is a focused, incremental refactor of the IRTF web site code to incorporate modern coding standards and best practices.

## Overview

The IRTF's web site codebase was written in procedural PHP many years ago and has grown increasingly difficult to maintain. This project focuses on refactoring the code incrementally into a modern PHP codebase following PSR-12 standards.

While most of the database migration to MariaDB has been completed, one legacy database remains on the old server due to compatibility issues. Completing the PHP refactor and updating the structure of a critical table will allow the final migration to the new server. By keeping the site operational during the transition, we aim to improve maintainability, enhance security, and future-proof the platform.

---

## Dependencies

To contribute to this project, ensure you have the following installed:
- PHP 7.2 or newer
- [Composer](https://getcomposer.org/) for managing dependencies
- [PHPUnit](https://phpunit.de/) for testing
- MariaDB (current version: 10.x) for database operations. Note: One legacy database is still on the old server, pending table structure updates.

---

## Getting Started

1. **Verify Dependencies**:
   Ensure you have PHP 7.2+ and Composer installed:
```bash
php -v
composer -v
```

2. **Cloning the Repository**:
```bash
git clone git@github.com:ifauh/webdev2024.git
cd webdev2024
```

3. **Creating a Branch**:
```bash
git checkout -b feature/short-description
```

4. **Submitting a Pull Request**:

- Push your branch to the remote repository.
- Open a pull request with a description of your changes and why they are needed.
- Assign reviewers if applicable.

5. **Install needed dependencies with [Composer](https://getcomposer.org/)**:
```bash
composer install
```

6. **A development server can be run for local testing and debugging**:
```bash
php -S localhost:8080 -t public_html
```

---

## Contributing

Please see [CONTRIBUTING.md](https://github.com/ifauh/webdev2024/blob/main/CONTRIBUTING.md) for the project's guidelines.

---

## Quick Git Workflow

For quick reference, here’s a basic Git workflow for small updates (e.g., documentation changes):

```bash
git status
git add CONTRIBUTING.md
git status
git commit -m "Update CONTRIBUTING.md with coding and workflow guidelines"
git push origin main
```

---

## Project Structure

The project follows a standard MVC pattern. Key directories include:

- `classes/core/`: Core utilities and shared logic.
  - `common/`: Reusable components like configuration handling and debugging tools.
  - `htmlbuilder/`: Classes for generating HTML elements and layouts.
  - `irtf/`: Application-specific utilities (e.g., link management).
- `classes/exceptions/`: Custom exception classes for structured error handling.
- `classes/schedule/`: Logic for building and uploading schedules.
  - `build/`: Classes for constructing schedules.
  - `upload/`: Classes for processing schedule uploads.
- `classes/services/`: Business logic and data interaction services.
  - `database/`: Database services and utilities.
  - `email/`: Services for sending and managing emails.
  - `files/`: Services for file handling.
  - `graphs/`: Services for creating graphs.
- `classes/views/`: Views for rendering HTML.
- `classes/validators/`: Validation logic for forms and input data.
- `classes/controllers/`: Controllers for handling requests and business logic.
- `classes/models/`: Models for database interaction.
- `configs/`: Configuration files for application settings, database connections, email, and debugging.
- `public_html/`: Public-facing assets and entry point.
- `tests/`: PHPUnit test cases.
- `classes/legacy/`: Wrapper classes providing a unified API for interacting with old procedural code. This area facilitates smooth integration between refactored and legacy components during the transition.

---

## License

This project is proprietary and intended for internal use by the IRTF team. Redistribution or use outside the organization is prohibited without explicit permission.

---

### Revision History
| Version | Date       | Author      | Description                       |
|---------|------------|-------------|-----------------------------------|
| 1.0     | 2024-12-04 | Miranda H-O | Initial draft of readme.md        |
| 1.1     | YYYY-MM-DD | Author Name | Description of changes            |
