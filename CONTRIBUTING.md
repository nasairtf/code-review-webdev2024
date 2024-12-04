# Contributing to IRTF Codebase

Welcome! This document outlines the coding standards, formatting rules, and best practices for contributing to the IRTF website project. Please review this guide before adding new features or making changes to ensure consistency and maintainability.

### Class and File Structure

1. **Class Layout**
   - Properties → Constructor → Abstract Methods → Public Methods → Protected Methods → Private Methods.
   - Abstract classes must define commonly used methods (e.g., `getFieldLabels` in form views).
   - Place abstract methods immediately after the constructor for clarity.

2. **File Organization**
   - Follow the existing directory structure (`controllers/`, `models/`, `views/`, etc.).
   - Use namespaces matching the directory structure.

3. **Naming Conventions**
   - Class names: `PascalCase` (e.g., `ScheduleManager`).
   - Properties and methods: `camelCase` (e.g., `$this->debugMode`).
   - Constants: `UPPER_SNAKE_CASE` (e.g., `MAX_RESULTS`).

### Coding Standards

1. **PSR-12 Compliance**
   - Indent with 4 spaces.
   - Line length: Max 120 characters.
   - Opening braces go on the same line.

2. **Strict Typing**
   - Enable strict typing in all new files: `declare(strict_types=1);`.
   - Use explicit types for parameters, return values, and properties.

3. **Error Handling**
   - Always throw exceptions for unrecoverable errors.
   - Catch exceptions only where they can be handled meaningfully (e.g., in controllers).

4. **Dependency Injection**
   - Use constructor injection for all dependencies.
   - Avoid global variables or singletons unless absolutely necessary.

### Debugging Rules

1. **Using the Debug Class**
   - Always inject the `Debug` instance via the constructor.
   - Use `Debug` categories (`'default'`, `'schedule'`, `'email'`, etc.) defined in `debug_config.php`.
   - Debug mode is `false` by default in production. Enable selectively for development.

2. **Debug Logging**
   - Use `$this->debug->log()` for informational messages.
   - Use `$this->debug->fail()` for critical errors, which automatically throw exceptions.

3. **PHPMailer Debugging**
   - Set PHPMailer debug levels via `applyMailerDebug()`.

### Formatting Rules

1. **HTML Output**
   - Use `$formatHtml` to toggle between readable and compact HTML.
   - Indentation controlled by the `$pad` parameter in HTML builders.

2. **Error Messages**
   - All error messages must be sanitized using `$this->htmlBuilder->escape()`.
   - Wrap error messages in `<p>` tags with `class="error-messages"`.

3. **Debug Output**
   - Debug messages must include method and class context (e.g., `"Controller: __construct"`).
   - Avoid leaking sensitive data in debug logs.

### DocBlock Standards

1. **Class-Level DocBlocks**
   - Include a brief description, category, package, author, and version.
   - Use `@category` and `@package` consistently.

2. **Method-Level DocBlocks**
   - Document all parameters and return values.
   - Use `@throws` to indicate exceptions a method can throw.

#### Example:
```php
/**
 * Renders the main form page.
 *
 * @param string $title    The title of the form page.
 * @param string $action   The form submission URL.
 * @param array  $dbData   Data arrays required to populate form options.
 * @param array  $formData Default data for form fields.
 * @param int    $pad      Optional padding level for formatted output (default: 0).
 *
 * @return string The complete HTML of the form page.
 *
 * @throws \Exception If rendering fails.
 */
public function renderFormPage(
    string $title = '',
    string $action = '',
    array $dbData = [],
    array $formData = [],
    int $pad = 0
): string {
    // Implementation
}

---

#### 6. **Commit Message Guidelines**
Explain how to write clear, useful commit messages.

```markdown
### Commit Message Guidelines

1. **Format**
   - Use imperative mood: "Add feature", "Fix bug", "Refactor class".
   - Include a brief summary in the first line (max 50 characters).
   - Optionally, add a detailed description in subsequent lines.

2. **Examples**
   - `Add dependency injection to ScheduleManager`
   - `Fix undefined index error in FeedbackController`
   - `Refactor EmailService for improved configurability`








