# Transactional Bundle

Adding this bundle enables support of `@Transactional` advice and pointcut.

The pointcut checks the existence of annotation `@Transactional`.

Join points will be executed within a transaction.

If join point executes successfully the EntityManager is flushed and transaction is committed.

If execution of join point threw an exception EntityManager is closed and transaction is rolled back.

## Usage
```bash
composer require dmp/transactional-bundle
```

## TODO
* add integration test
