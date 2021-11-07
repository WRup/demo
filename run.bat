:: Setup database
@echo off
set /p drive=Prosze podac silnik bazy danych [mysql, mariadb]:
@echo off
set /p username=Prosze podac nazwe uzytkownika bazy danych:
@echo off
set /p password=Prosze podac haslo bazy danych:
@echo off
set /p host=Prosze podac hosta bazy danych:
@echo off
set /p port=Prosze podac port bazy danych:
@echo off
set /p version=Prosze podac wersje bazy danych:
if %drive%==mariadb set DATABASE_URL=mysql://%username%:%password%@%host%:%port%/labinventory?serverVersion=%drive%-%version%
if %drive%==mysql set DATABASE_URL=%drive%://%username%:%password%@%host%:%port%/labinventory?serverVersion=%version%
echo Following DATABASE_URL env will be created %DATABASE_URL%
::Install node modules
npm install

::Install required libs
call composer install
if %errorlevel% neq 0 exit /b %errorlevel%

::Install css/js
call yarn dev
if %errorlevel% neq 0 exit /b %errorlevel%

::Create and fulfill database
php bin/console doctrine:database:create
if %errorlevel% neq 0 exit /b %errorlevel%
php bin/console make:migration
if %errorlevel% neq 0 exit /b %errorlevel%
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
if %errorlevel% neq 0 exit /b %errorlevel%

::start application
symfony serve
EXIT /B %ERRORLEVEL%





