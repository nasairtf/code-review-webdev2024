# Cliffnotes for the IRTF WebDev2024 Codebase

---

1. **Class layout**:
        1. Properties
        2. Constructor
        3. Abstract Methods
        4. Public Methods
        5. Protected Methods
        6. Private Methods

2. **Naming Conventions**
    - Class names: `PascalCase` (e.g., `ScheduleManager`).
    - Properties and methods: `camelCase` (e.g., `$this->debugMode`).
    - Constants: `UPPER_SNAKE_CASE` (e.g., `MAX_RESULTS`).
    - Class file names: `PascalCaseClassName.php` (e.g., `ScheduleManager.php`).
    - Directory names: `classes/lower/case/names/` (e.g., `/home/webdev2024/classes/views/forms/`).
    - Namespace names: `App\lower\case\dir;` (e.g., `App\views\forms;`).
    - Use names: `App\lower\case\dir\ClassName;` (e.g., `App\views\forms;`).
    - Use aliases for parent classes, service classes, and standard model, view, validator classes (e.g., `use App\views\forms\BaseFormView as BaseView;`, class UploadScheduleFileView extends BaseView).

3. **Git**
    ```bash
    git status
    git add CONTRIBUTING.md
    git status
    git commit -m "Update stuff"
    git push origin main
    ```

---

### Debug Categories
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

### Revision History
| Version | Date       | Author      | Description                       |
|---------|------------|-------------|-----------------------------------|
| 1.0     | 2024-12-07 | Miranda H-O | Initial draft of cliffnotes.md    |
| 1.1     | YYYY-MM-DD | Author Name | Description of changes            |
