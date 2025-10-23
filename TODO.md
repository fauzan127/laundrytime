# TODO: Create Unit and Feature Tests for OrderController

## Unit Tests (tests/Unit/OrderControllerTest.php)
- [x] Create tests/Unit/OrderControllerTest.php file
- [x] Add unit tests for index method (2 cases: success, error)
- [x] Add unit tests for create method (2 cases: success, error)
- [x] Add unit tests for store method (2 cases: success, validation error)
- [x] Add unit tests for show method (2 cases: success, not found)
- [x] Add unit tests for edit method (2 cases: admin success, unauthorized)
- [x] Add unit tests for update method (2 cases: admin success, unauthorized)
- [x] Add unit tests for destroy method (2 cases: admin success, unauthorized)

## Feature Tests (tests/Feature/OrderControllerTest.php)
- [x] Create tests/Feature/OrderControllerTest.php file
- [x] Add feature tests for index method (2 cases: authenticated, unauthenticated)
- [x] Add feature tests for create method (2 cases: authenticated, unauthenticated)
- [x] Add feature tests for store method (2 cases: valid data, invalid data)
- [x] Add feature tests for show method (2 cases: existing order, non-existing order)
- [x] Add feature tests for edit method (2 cases: admin access, non-admin access)
- [x] Add feature tests for update method (2 cases: admin update, non-admin update)
- [x] Add feature tests for destroy method (2 cases: admin delete, non-admin delete)

## Verification
- [ ] Run tests with `php artisan test`
- [ ] Verify all tests pass
- [ ] Check test coverage for validation, authorization, success/error cases
