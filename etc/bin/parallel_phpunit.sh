find src/* -name "*Test.php" | ./bin/fastest --before="./etc/bin/initORM.php test" --verbose --preserve-order --process=3 "bin/phpunit -c app {};"
