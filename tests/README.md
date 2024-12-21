# Testing for the IRTF WebDev2024 Codebase

---

1. **Available Tools**:
    - [`PHPUnit`](https://github.com/sebastianbergmann/phpunit)
    - `Mockery`
    - [tests/utilities](https://github.com/ifauh/webdev2024/tree/main/tests/utilities)
        - [`ConfigMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/ConfigMockTrait.php)
        - [`DebugMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/DebugMockTrait.php), [`CustomDebugMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/CustomDebugMockTrait.php)
        - [`DBConnectionMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/DBConnectionMockTrait.php), [`MySQLiWrapperMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/MySQLiWrapperMockTrait.php)
        - [`DatabaseServiceMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/DatabaseServiceMockTrait.php)
        - [`MockBehaviorTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/MockBehaviorTrait.php), [`PrivatePropertyTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/PrivatePropertyTrait.php)
    - Note: use [`DebugMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/DebugMockTrait.php) or [`CustomDebugMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/CustomDebugMockTrait.php) depending on the version of the [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) class used in the class under test. [`CustomDebug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/CustomDebug.php) extends [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) with additional exception types and `fail*()` methods.

2. **Test Methodology**:
    - Ensure protected methods are tested via derived class proxy method exposure.
    - Use `setUp()` to configure common mocks and methods used by all unit tests in a class and execute the parent class `setUp()` method.
    - Use `tearDown()` to clean up Mock expectations and execute the parent class `tearDown()` method.
    - Use `createTestData()` to generate all test data. Include inputs, outputs, and relevant variables in the array.
    - Use `createTestQueryParts()` to generate the SQL parts. Include SQL strings, params arrays, and type strings as needed.

3. **Debug Class**:
    - Ensure the [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) class instances' `debugMode` is set to false.
    - If `debugMode` cannot be set to false, ensure `debugLevel` is set to 0.
    - [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) throws `Exceptions` in `fail()`.
    - [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) writes to `error_log` in `log()` if `debugLevel` is >0.
    - [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) echos output if `debugMode` is true.
    - Ensure unit tests take this into consideration.
    - Mocking [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) or disabling `debugMode`, `debugLevel` ensures no test interference.
    - [`CustomDebug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/CustomDebug.php) extends [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) with additional custom exception types and methods to throw these exceptions.
    - See [`classes/exceptions`](https://github.com/ifauh/webdev2024/tree/main/classes/exceptions) for more informaton on the exceptions available.

4. **Traits**:
    - There is a collection of assorted Traits in [`tests/utilities`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities).
    - The Traits contain code used to standardize Mocking of certain oft-used classes.
    - Here is a non-inclusive list of the key traits and mocks:
        - [`ConfigMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/ConfigMockTrait.php) -- Mocks the core Config class.
            - createConfigMock()
            - loadMockConfig()
        - [`DebugMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/DebugMockTrait.php) -- Mocks the core Debug class.
            - createDebugMock()
            - mockDebug()
            - mockFail()
        - [`CustomDebugMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/CustomDebugMockTrait.php) -- Mocks the core CustomDebug class.
            - createCustomDebugMock()
            - mockDebug()
            - mockFail()
        - [`MySQLiWrapperMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/MySQLiWrapperMockTrait.php) -- Mocks the MySQLiWrapper class used by DBConnection.
            - createMySQLiWrapperMock()
        - [`DBConnectionMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/DBConnectionMockTrait.php) -- Mocks the core database interface DBConnection class.
            - createDBConnectionMock()
            - mockGetInstance()
        - [`DatabaseServiceMockTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/DatabaseServiceMockTrait.php) -- Partially mocks the core database ancestor DatabaseService class.
            - createPartialDatabaseServiceMock()
            - mockFetchDataWithQuery()
            - mockModifyDataWithQuery()
            - mockExecuteSelectQuery()
            - mockExecuteUpdateQuery()
            - mockGetSortString()
        - [`MockBehaviorTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/MockBehaviorTrait.php) -- Mocks method expectations and assertions.
            - arrangeMockBehavior()
            - assertMockBehavior()
            - arrangeTransactions()
            - assertTransactions()
        - [`PrivatePropertyTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/PrivatePropertyTrait.php) -- Allows access to private and protected properties.
            - getPrivateProperty()
            - setPrivateProperty()
            - assertDependency()

5. **Examples**:

Example Output for `createTestQueryParts()`:
```php
[
    'sql' => 'SELECT * FROM table WHERE id = ?',
    'params' => [123],
    'types' => 'i',
]
```
---

### Revision History
| Version | Date       | Author      | Description                       |
|---------|------------|-------------|-----------------------------------|
| 1.0     | 2024-12-10 | Miranda H-O | Initial draft of readme.md        |
| 1.1     | 2024-12-20 | Miranda H-O | Added additional information      |
| 1.2     | YYYY-MM-DD | Author Name | Description of changes            |
