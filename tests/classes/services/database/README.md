# Database service classes tests

The `MySQLiWrapper` mysqli wrapper class cannot be tested using standard unit testing
procedures, because PHP loads built in classes prior to any other classes. This means
that any attempt to mock built in classes fails due to existing class conflicts.
