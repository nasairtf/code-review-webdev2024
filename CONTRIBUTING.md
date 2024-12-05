# Guidelines for the IRTF WebDev2024 Codebase

### Welcome!

This document serves as a guide for SE team members working on the IRTF website PHP refactor project. It outlines coding standards, formatting rules, workflow practices, and debugging guidelines to maintain consistency and streamline collaboration.

---

#### Table of Contents

* [TL;DR Coding Guidelines](#tldr-coding-guidelines)
* [Workflow](#workflow)
* [Testing](#testing)
* [Debugging Rules](#debugging-rules)
* [Coding Standards](#coding-standards)
* [Formatting Rules](#formatting-rules)
* [DocBlock Standards](#docblock-standards)
* [Commit Message Guidelines](#commit-message-guidelines)
* [Code Examples](#code-examples)
    * [Example Reference List](#example-reference-list)
* [Tables](#tables)
    * [Debug Categories](#debug-categories)
* [Resources](#resources)

---

### TL;DR Coding Guidelines

1. **PSR-12 Compliance**:
    - All code must adhere to [PSR-12 standards](https://www.php-fig.org/psr/psr-12).
    - Use [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) (`phpcs`) to check your code.
    - The config file `phpcs.xml` is in the project root.

2. **PHP Version**:
    - Ensure PHP 7.2 compatibility for all development.
    - Production server uses PHP 7.2 as of 2024/12/04.

3. **File and Directory Naming**:
    - Use `CamelCase` for class files (e.g., `MyClass.php`).
    - Use `snake_case` for configuration and script files (e.g., `my_config.php`).
    - Follow existing directory and namespace structures (e.g., `classes/services/files/FileParser.php`).
    - Use namespaces that correspond to the directory structure (e.g., `App\services\files`).
    - See [STRUCTURE.md](https://github.com/ifauh/webdev2024/blob/main/STRUCTURE.md) for more details.

4. **Coding Practices**:
    - Functions and methods should have clear, descriptive names.
    - Write comments for complex logic but avoid unnecessary comments.

5. **Code Style**:
    - Indentation: 4 spaces (no tabs).
    - Use single quotes for strings unless the string contains variables or escaped characters.
    - Class layout:
        1. Properties
        2. Constructor
        3. Abstract Methods
        4. Public Methods
        5. Protected Methods
        6. Private Methods
    - Constructor signatures: use dependency injection when possible.

6. **Workflow**:
    - Use a new branch for each feature or bug fix.
    - Test thoroughly using [PHPUnit](https://github.com/sebastianbergmann/phpunit) before committing.
    - Tests should be run and pass locally before submitting code for review.
    - Submit pull requests for review if the change is significant.
    - Tag in Slack for help as needed.

7. **Version Control / Commits**:
    - Always work on a branch and submit pull requests for review.
    - Make granular commits with meaningful messages.
    - Write clear, concise commit messages in the imperative tense (e.g., "Add user authentication to API").
    - Example: `git commit -m "Fix issue with header rendering"`

8. **Contact info**:
    - Slack: @Miranda, @Anubhav
    - Email: hawarden@hawaii.edu or irtf-it-group@lists.hawaii.edu

9. **Additional Notes**:
    - Consult Miranda before making structural changes, introducing new dependencies, or significant refactors.
    - Questions or non-urgent items can be discussed at the Monday status meeting.

10. **Security**:
    - Sanitize all user inputs and outputs using built-in methods.
    - Avoid using global variables for sensitive data.

---

### Workflow

1. **Cloning the Repository**:
```bash
git clone git@github.com:ifauh/webdev2024.git
cd webdev2024
```

2. **Creating a Branch**:
```bash
git checkout -b feature/short-description
```

3. **Submitting a Pull Request**:

    - Push your branch to the remote repository.
    - Open a pull request with a description of your changes and why they are needed.
    - Assign reviewers if applicable.

4. **Install needed dependencies with [Composer](https://getcomposer.org/)**:
```bash
composer install
```

5. **A development server can be run for local testing and debugging**:
```bash
php -S localhost:8080 -t public_html
```

---

### Testing

1. **Testing**:
    - Use [PHPUnit](https://github.com/sebastianbergmann/phpunit) for testing.
    - Test files should be in the `tests` directory, following the same structure as the source code.
    - Use descriptive method names for tests.

---

### Debugging Rules

1. **Using the Debug Class**
    - Debug output and logging should be disabled by default in production.
        - Debug mode is `false` by default. Enable selectively for development.
        - Debug level is `0` by default. Enable selectively for development.
    - Debug mode and level can only be set during instantiation:
        - Setting `debugMode` to `true` when creating a `Debug` instance. This allows calls to `$this->debug->debug()` to print the colour-coded debugging output.
        - Setting `debugLevel` to `1` when creating a `Debug` instance. This allows calls to `$this->debug->log()` to write to the `ssl_error_log`.
    - `Debug` categories provide colour-coded debug output:
        - Categories (`'default'`, `'schedule'`, `'email'`, etc.) are defined in `debug_config.php`.
        - Existing categories are assigned to various domain and service classes.
        - If a new class will reside in one of the existing namespaces, it should use the colour of that category.
        - For example, a class to provide access to a new database should use the `database` category/colour for its `Debug` instance.
    - Domains and top-level services should instantiate their own `Debug` instance to differentiate output within the workflow (e.g., `ScheduleManager`, `DatabaseService`, `EmailService`, etc).
    - Always inject the `Debug` instance via the constructor for forms and display pages:
        - Define a single `Debug` instance in the `EntryPoint.php`.
        - Pass the `Debug` instance via the constructors to the form-specific `EntryPointController`, `EntryPointModel`, `EntryPointView`, and `EntryPointValidator` classes.
        - This will maintain consistent `Debug` output across the form.
    - See the [Debug Table](#debug-categories) for the `Debug` categories.
    - See the [Examples](#code-examples) for code examples and links.

2. **Debug Logging**
   - Use `$this->debug->fail()` for critical errors, which automatically throw exceptions.
   - Use `$this->debug->log()` for messages written to the `ssl_error_log`.
   - Use `$this->debug->debug()` for informational messages.
   - Use `$this->debug->debugHeading()` to output class- and method-specific information at the method level.
   - Use `$this->debug->debugVariables()` to output variables, including arrays.
   - Using these calls consistently across a class's methods provides a detailed execution trace during development or debugging.

3. **PHPMailer Debugging**
   - The `EmailService` class is the base-level `Email` class, extended by any function-specific service classes.
   - Set PHPMailer debug levels via `applyMailerDebug()`.

---

### Coding Guidelines

#### Coding Standards

1. **PSR-12 Overview**:
    - Use the full PHP opening tag (`<?php`).
    - Indent with 4 spaces.
    - Line length: Max 120 characters.
    - Opening braces go on the same line as the declaration.
    - Namespace and `use` declarations go at the top of the file after `declare(strict_types=1);`.
    - Leave a single blank line between namespace declarations and use statements for readability.
    - Avoid global variables or singletons unless absolutely necessary (e.g. `DB` connection pool).
    - Abstract classes must define commonly used methods such as getFieldLabels in form views.

2. **Strict Typing**
    - Enable strict typing in all new files: `declare(strict_types=1);`.
    - Use explicit types for parameters, return values, and properties.
    - Note that PHP 7.2 does not allow mixed return types.

3. **Function and Method Guidelines**:
    - Functions must have clear input and output definitions.
    - Use type declarations for parameters and return types.

4. **Dependency Injection**
    - Use constructor injection for all dependencies.
    - Avoid global variables unless absolutely necessary.
    - Only use singletons if their usage enhances efficiency.
    - The low level database interaction class DB uses a singleton and a connection pool.

5. **Error Handling**
    - Always throw exceptions for unrecoverable errors.
    - Catch exceptions only where they can be handled meaningfully (e.g., in controllers).
    - Rethrow exceptions as needed.
    - Follow existing code conventions for user-actionable exceptions and non-actionable exceptions.
    - User-actionable exceptions can result in an ErrorBlock displayed with the form rendered below the block.
    - Non-user-actionable exceptions should halt execution for developer intervention.

6. **PHP CodeSniffer (PHPCS)**:
    - Run `phpcs --standard=PSR12` to check your code before committing.
    - If your branch has new sniffs, include a note in your pull request with instructions on how to use them.

7. **Custom Sniffs**:
    - Since custom sniffs are in development, use default PSR-12 rules for now.
    - Future updates to this guide will include instructions for using the organization-specific rules.

#### Class and File Structure

1. **Class Layout**
    - Classes should follow a specific structure:
        - Properties 
        - Constructor 
        - Abstract Methods 
        - Public Methods 
        - Protected Methods 
        - Private Methods
    - Abstract classes must define commonly used methods (e.g., `getFieldLabels` and `getPageContents` in form views).
    - Place abstract methods immediately after the constructor for clarity.

2. **File Organization**
    - Follow the existing directory structure (`controllers/`, `models/`, `views/`, etc.).
    - Use namespaces that correspond to the directory structure for consistency.

3. **Naming Conventions**
    - Class names: `PascalCase` (e.g., `ScheduleManager`).
    - Properties and methods: `camelCase` (e.g., `$this->debugMode`).
    - Constants: `UPPER_SNAKE_CASE` (e.g., `MAX_RESULTS`).
    - Class file names: `PascalCaseClassName.php` (e.g., `ScheduleManager.php`).
    - Directory names: `classes/lower/case/names/` (e.g., `/home/webdev2024/classes/views/forms/`).
    - Namespace names: `App\lower\case\dir;` (e.g., `App\views\forms;`).
    - Use names: `App\lower\case\dir\ClassName;` (e.g., `App\views\forms;`).
    - Use aliases for parent classes, service classes, and standard model, view, validator classes (e.g., `use App\views\forms\BaseFormView as BaseView;`, class UploadScheduleFileView extends BaseView).

### Formatting Rules

1. **HTML Output**
   - Use `$formatHtml` to toggle between readable and compact HTML.
   - Indentation controlled by the `$pad` parameter in HTML builders.

2. **Error and Results Messages**
    - The `BaseFormView` class provides the method `renderFormWithErrors` to render a block display of errors.
    - A comparative method `renderPageWithResults` renders a block display of results.
    - Both methods take an array of message-strings and displays the list.
    - If not using the `BaseFormView` methods above:
        - All error and results messages must be sanitized using `$this->htmlBuilder->escape()`.
        - Wrap error messages in `<p>` tags with `class="error-messages"`.
        - Wrap result messages in `<p>` tags with `class="result-messages"`.

3. **Debug Output**
    - Debug messages must include method and class context (e.g., `"Controller: __construct"`).
    - Avoid leaking sensitive data in debug logs.
    - Take advantage of the method `$this->debug->debugHeading()` to generate context.

### DocBlock Standards

1. **Class-Level DocBlocks**
    - Include a brief description, category, package, author, and version.
    - Use `@category` and `@package` consistently.

2. **Method-Level DocBlocks**
    - Document all parameters and return values.
    - Use `@throws` to indicate exceptions a method can throw.

3. **Examples**
    - See the [Examples](#code-examples) for code examples and links.

---

### Commit Message Guidelines

1. **Format**
    - Use imperative mood: "Add feature", "Fix bug", "Refactor class".
    - Include a brief summary in the first line (max 50 characters).
    - Optionally, add a detailed description in subsequent lines.

---

### Code Examples

#### Example Reference List:
* [Debug (initial version)](https://github.com/ifauh/webdev2024/blob/dbdfe8d55bd2af11363fa7dfc3ad95aae1bac36b/classes/core/common/Debug.php)
* [FeedbackController (initial version)](https://github.com/ifauh/webdev2024/blob/dbdfe8d55bd2af11363fa7dfc3ad95aae1bac36b/classes/controller/feedback/FeedbackController.php)
* [BaseFormView (initial version)](https://github.com/ifauh/webdev2024/blob/dbdfe8d55bd2af11363fa7dfc3ad95aae1bac36b/classes/views/forms/BaseFormView.php)

#### Class File Example:
```php
<?php
declare(strict_types=1);

namespace App\controllers\feedback;

use Exception;

use App\exceptions\ValidationException;
use App\core\common\Config;
use App\core\common\Debug;
use App\services\email\feedback\FeedbackService as Email;

use App\models\feedback\FeedbackModel as Model;
use App\views\forms\feedback\FeedbackView as View;
use App\validators\forms\feedback\FeedbackValidator as Validator;

/**
 * Controller for handling the Feedback form logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FeedbackController
{
    private $formatHtml;
    private $debug;
    private $model;
    private $view;
    private $valid;
    private $email;
    private $redirect;

    /**
     * Constructs the Controller, initializing all required dependencies.
     *
     * @param bool|null      $formatHtml Enable or disable HTML formatting (default: false).
     * @param Debug|null     $debug      Debug instance for logging and debugging (default: new Debug instance).
     * @param Model|null     $model      Model instance (default: new Model).
     * @param View|null      $view       View instance (default: new View).
     * @param Validator|null $valid      Validator instance (default: new Validator).
     * @param Email|null     $email      Email instance (default: new Email).
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?Model $model = null,
        ?View $view = null,
        ?Validator $valid = null,
        ?Email $email = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml ?? false;

        // Fetch the Feedback form config from Config
        $this->redirect = Config::get('feedback_config', 'redirect')['login'] ?? '';
        $this->debug->log("{$debugHeading} -- Config successfully fetched.");

        // Initialise dependencies with fallbacks
        $this->model = $model ?? new Model($this->debug);
        $this->view = $view ?? new View($this->formatHtml, $this->debug);
        $this->valid = $valid ?? new Validator($this->debug);
        $this->debug->log("{$debugHeading} -- Model, View, Validator classes successfully initialised.");

        // Initialise the additional classes needed by this controller
        $this->email = $email ?? new Email($this->debug->isDebugMode());
        $this->debug->log("{$debugHeading} -- Email class successfully initialised.");

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- Controller initialisation complete.");
    }
```

#### Method-Level DocBlock Example:
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
```

#### Commit Message Examples:
```php
- `Add dependency injection to ScheduleManager`
- `Fix undefined index error in FeedbackController`
- `Refactor EmailService for improved configurability`
```

```php
`Add error handling to FeedbackController to prevent crashes`

- Improved validation for input data.
- Added unit tests for edge cases.
```

---

### Tables

#### Debug Categories
| Debug Category | Colour | Description                  |
|----------------|--------|------------------------------|
| `default`      | `#008000` | General page output.         |
| `file`         | `#add8e6` | File service classes.        |
| `db`           | `#ffff00` | Lowest level DB API.         |
| `database`     | `#ffa500` | Database service classes.    |
| `email`        | `#800080` | Email service classes.       |
| `graph`        | `#e0b0ff` | Graphing service classes.    |
| `login`        | `#00ffff` | Login portal form classes.   |
| `schedule`     | `#ff00ff` | Schedule domain classes.     |

---

### Resources
* [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12)
* [Composer](https://getcomposer.org/)
* [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHPUnit](https://github.com/sebastianbergmann/phpunit)
* [PHPUnit Documentation](https://phpunit.de/documentation.html)

---

### Revision History
| Version | Date       | Author      | Description                       |
|---------|------------|-------------|-----------------------------------|
| 1.0     | 2024-12-04 | Miranda H-O | Initial draft of contributing.md  |
| 1.1     | YYYY-MM-DD | Author Name | Description of changes            |
