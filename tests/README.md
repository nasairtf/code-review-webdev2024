# Testing for the IRTF WebDev2024 Codebase

---

1. **Available Tools**:
    - [`PHPUnit`](https://github.com/sebastianbergmann/phpunit)
    - `Mockery`
    - [tests/utilities](https://github.com/ifauh/webdev2024/tree/main/tests/utilities)
        - [`MockConfigTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockConfigTrait.php)
        - [`MockDebugTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDebugTrait.php), [`MockDebugTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDebugTrait.php)
        - [`MockDBConnectionTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDBConnectionTrait.php), [`MockMySQLiWrapperTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockMySQLiWrapperTrait.php)
        - [`MockDatabaseServiceCoreTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceCoreTrait.php), [`MockDatabaseServiceExecuteSelectQueryTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceExecuteSelectQueryTrait.php), [`MockDatabaseServiceExecuteUpdateQueryTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceExecuteUpdateQueryTrait.php), [`MockDatabaseServiceFetchDataWithQueryTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceFetchDataWithQueryTrait.php), [`MockDatabaseServiceModifyDataWithQueryTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceModifyDataWithQueryTrait.php)
        - [`ArrangeBehaviorTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/MockBehaviorTrait.php), [`PrivatePropertyHelperTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/helpers/PrivatePropertyHelperTrait.php)
    - Note: use [`MockDebugTrait::createDebugMock`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDebugTrait.php) or [`MockDebugTrait::createCustomDebugMock`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDebugTrait.php) depending on the version of the [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) class used in the class under test. [`CustomDebug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/CustomDebug.php) extends [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) with additional exception types and `fail*()` methods.

2. **Test Methodology**:
    - Ensure protected methods are tested via derived class proxy method exposure.
    - Use `setUp()` to configure common mocks and methods used by all unit tests in a class and execute the parent class `setUp()` method.
    - Use `tearDown()` to clean up Mock expectations and execute the parent class `tearDown()` method.
    - Use `createTestData()` to generate all test data. Include inputs, outputs, and relevant variables in the array. Make use of `createTestQueryParts()` to generate the necessary query parts for use with mocked query methods, and so on.
    - Use `createTestQueryParts()` to generate the SQL parts. Include SQL strings, params arrays, type strings, expected return rows, and error messages, as needed.

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
        - [`MockConfigTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/MockConfigTrait.php) -- Mocks the core [`Config`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Config.php) class.
            - createConfigMock()
            - loadMockConfig()
        - [`MockDebugTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDebugTrait.php) -- Mocks the core [`Debug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/Debug.php) class and the [`CustomDebug`](https://github.com/ifauh/webdev2024/tree/main/classes/core/common/CustomDebug.php) class.
            - createDebugMock()
            - createCustomDebugMock()
            - mockDebug()
            - mockFail()
            - mockMultipleFails()
        - [`MockMySQLiWrapperTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockMySQLiWrapperTrait.php) -- Mocks the [`MySQLiWrapper`](https://github.com/ifauh/webdev2024/tree/main/classes/services/database/MySQLiWrapper.php) class used by [`DBConnection`](https://github.com/ifauh/webdev2024/tree/main/classes/services/database/DBConnection.php).
            - createMySQLiWrapperMock()
        - [`MockDBConnectionTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDBConnectionTrait.php) -- Mocks the core database interface [`DBConnection`](https://github.com/ifauh/webdev2024/tree/main/classes/services/database/DBConnection.php) class.
            - createDBConnectionMock()
            - mockGetInstance()
        - [`MockDatabaseServiceCoreTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceCoreTrait.php) -- Partially mocks the core database ancestor [`DatabaseService`](https://github.com/ifauh/webdev2024/tree/main/classes/services/database/DatabaseService.php) class.
            - createPartialDatabaseServiceMock()
            - arrangeQueryExpectationBehavior()
            - mockGetSortString()
        - [`MockDatabaseServiceExecuteSelectQueryTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceExecuteSelectQueryTrait.php) -- Handles mocking of the executeSelectQuery method and its associated expectations and assertions.
            - arrangeExecuteSelectQueryExpectations()
            - assertExecuteSelectQueryExpectations()
            - mockExecuteSelectQuery()
        - [`MockDatabaseServiceExecuteUpdateQueryTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceExecuteUpdateQueryTrait.php) -- Handles mocking of the executeUpdateQuery method and its associated expectations and assertions.
            - arrangeExecuteUpdateQueryExpectations()
            - assertExecuteUpdateQueryExpectations()
            - mockExecuteUpdateQuery()
        - [`MockDatabaseServiceFetchDataWithQueryTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceFetchDataWithQueryTrait.php) -- Handles mocking of the fetchDataWithQuery method and its associated expectations and assertions.
            - arrangeFetchDataWithQueryExpectations()
            - assertFetchDataWithQueryExpectations()
            - mockFetchDataWithQuery()
        - [`MockDatabaseServiceModifyDataWithQueryTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/mocks/MockDatabaseServiceModifyDataWithQueryTrait.php) -- Handles mocking of the fetchDataWithQuery method and its associated expectations and assertions.
            - arrangeModifyDataWithQueryExpectations()
            - assertModifyDataWithQueryExpectations()
            - mockModifyDataWithQuery()
        - [`ArrangeBehaviorTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/assertions/ArrangeBehaviorTrait.php) -- Mocks method expectations and assertions.
            - arrangeMockBehavior()
            - assertMockBehavior()
            - arrangeTransactions()
            - assertTransactions()
        - [`PrivatePropertyHelperTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/helpers/PrivatePropertyHelperTrait.php) -- Allows access to private and protected properties.
            - getPrivateProperty()
            - setPrivateProperty()
        - [`AssertPrivateDependenciesTrait`](https://github.com/ifauh/webdev2024/tree/main/tests/utilities/assertions/AssertPrivateDependenciesTrait.php) -- Allows assertions on private and protected properties.
            - assertPrivateDependency()

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
| 1.2     | 2024-12-29 | Miranda H-O | Updated info from trait refactors |
| 1.3     | YYYY-MM-DD | Author Name | Description of changes            |
