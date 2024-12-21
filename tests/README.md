# Testing for the IRTF WebDev2024 Codebase

---

1. **Available Tools**:
    - PHPUnit
    - Mockery
    - tests/utilities
        - ConfigMockTrait
        - DebugMockTrait, CustomDebugMockTrait
        - DBConnectionMockTrait, MySQLiWrapperMockTrait
        - DatabaseServiceMockTrait
        - MockBehaviorTrait, PrivatePropertyTrait
    - Note: use DebugMockTrait or CustomDebugMockTrait depending on the version used in the class under test.

2. **Test Methodology**:
    - Ensure protected methods are tested via derived class proxy method exposure.
    - Use setUp() to configure common mocks and methods used by all unit tests in a class and execute the parent class setUp method.
    - Use tearDown() to clean up Mock expectations and execute the parent class tearDown method.
    - Use createTestData() to generate all test data. Include inputs, outputs, and relevant variables in the array.
    - Use createTestQueryParts() to generate the SQL parts. Include SQL strings, params arrays, and type strings as needed.

2. **Debug Class**:
    - Ensure the Debug class instances' debugMode is set to false.
    - If debugMode cannot be set to false, ensure debugLevel is set to 0.
    - Debug throws Exceptions in fail().
    - Debug writes to error_log in log() if debugLevel is >0.
    - Debug echos output if debugMode is true.
    - Ensure unit tests take this into consideration.
    - Mocking Debug or disabling debugMode/debugLevel ensures no test interference.

3. **Traits**:
    - There is a collection of assorted Traits in `tests/utilities`.
    - The Traits contain code used to standarise Mocking of certain oft-used classes.
    - Here is a non-inclusive list of the key traits and mocks:
        - ConfigMockTrait -- Mocks the core Config class.
            - createConfigMock()
            - loadMockConfig()
        - DebugMockTrait -- Mocks the core Debug class.
            - createDebugMock()
            - mockDebug()
            - mockFail()
        - CustomDebugMockTrait -- Mocks the core CustomDebug class.
            - createCustomDebugMock()
            - mockDebug()
            - mockFail()
        - MySQLiWrapperMockTrait -- Mocks the MySQLiWrapper class used by DBConnection.
            - createMySQLiWrapperMock()
        - DBConnectionMockTrait -- Mocks the core database interface DBConnection class.
            - createDBConnectionMock()
            - mockGetInstance()
        - DatabaseServiceMockTrait -- Partially mocks the core database ancestor DatabaseService class.
            - createPartialDatabaseServiceMock()
            - mockFetchDataWithQuery()
            - mockModifyDataWithQuery()
            - mockExecuteSelectQuery()
            - mockExecuteUpdateQuery()
            - mockGetSortString()
        - MockBehaviorTrait -- Mocks method expectations and assertions.
            - arrangeMockBehavior()
            - assertMockBehavior()
            - arrangeTransactions()
            - assertTransactions()
        - PrivatePropertyTrait -- Allows access to private and protected properties.
            - getPrivateProperty()
            - setPrivateProperty()
            - assertDependency()

---

### Revision History
| Version | Date       | Author      | Description                       |
|---------|------------|-------------|-----------------------------------|
| 1.0     | 2024-12-10 | Miranda H-O | Initial draft of readme.md        |
| 1.1     | 2024-12-20 | Miranda H-O | Added additional information      |
| 1.2     | YYYY-MM-DD | Author Name | Description of changes            |
