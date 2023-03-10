# Installation

Добавить в kernel

    Evrinoma\CertBundle\EvrinomaCertBundle::class => ['all' => true],

Добавить в routes

    cert:
        resource: "@EvrinomaCertBundle/Resources/config/routes.yml"

Добавить в composer

    composer config repositories.dto vcs https://github.com/evrinoma/DtoBundle.git
    composer config repositories.dto-common vcs https://github.com/evrinoma/DtoCommonBundle.git
    composer config repositories.utils vcs https://github.com/evrinoma/UtilsBundle.git

# Configuration

преопределение штатного класса сущности

    cert:
        db_driver: orm модель данных
        factory: App\Cert\Factory\CertFactory фабрика для создания объектов,
                 недостающие значения можно разрешить только на уровне Mediator
        entity: App\Cert\Entity\Cert сущность
        constraints: Вкл/выкл проверки полей сущности по умолчанию 
        dto: App\Cert\Dto\CertDto класс dto с которым работает сущность
        decorates:
          command - декоратор mediator команд сертификата
          query - декоратор mediator запросов сертификата
        services:
          pre_validator - переопределение сервиса валидатора сертификата
          handler - переопределение сервиса обработчика сущностей
          file_system - переопределение сервиса сохранения файла

# CQRS model

Actions в контроллере разбиты на две группы
создание, редактирование, удаление данных

        1. putAction(PUT), postAction(POST), deleteAction(DELETE)
получение данных

        2. getAction(GET), criteriaAction(GET)

каждый метод работает со своим менеджером

        1. CommandManagerInterface
        2. QueryManagerInterface

При переопределении штатного класса сущности, дополнение данными осуществляется декорированием, с помощью MediatorInterface


группы  сериализации

    1. API_GET_CERT, API_CRITERIA_CERT - получение сертификата
    2. API_POST_CERT - создание сертификата
    3. API_PUT_CERT -  редактирование сертификата

# Статусы:

    создание:
        сертификат создан HTTP_CREATED 201
    обновление:
        сертификат обновлен HTTP_OK 200
    удаление:
        сертификат удален HTTP_ACCEPTED 202
    получение:
        сертификат найден HTTP_OK 200
    ошибки:
        если сертификат не найден CertNotFoundException возвращает HTTP_NOT_FOUND 404
        если сертификат не уникален UniqueConstraintViolationException возвращает HTTP_CONFLICT 409
        если сертификат не прошел валидацию CertInvalidException возвращает HTTP_UNPROCESSABLE_ENTITY 422
        если сертификат не может быть сохранен CertCannotBeSavedException возвращает HTTP_NOT_IMPLEMENTED 501
        все остальные ошибки возвращаются как HTTP_BAD_REQUEST 400

# Constraint

Для добавления проверки поля сущности cert нужно описать логику проверки реализующую интерфейс Evrinoma\UtilsBundle\Constraint\Property\ConstraintInterface и зарегистрировать сервис с этикеткой evrinoma.cert.constraint.property

    evrinoma.cert.constraint.property.custom:
        class: App\Cert\Constraint\Property\Custom
        tags: [ 'evrinoma.cert.constraint.property' ]

## Description
Формат ответа от сервера содержит статус код и имеет следующий стандартный формат
```text
    [
        TypeModel::TYPE => string,
        PayloadModel::PAYLOAD => array,
        MessageModel::MESSAGE => string,
    ];
```
где
TYPE - типа ответа

    ERROR - ошибка
    NOTICE - уведомление
    INFO - информация
    DEBUG - отладка

MESSAGE - от кого пришло сообщение
PAYLOAD - массив данных

## Notice

показать проблемы кода

```bash
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --verbose --diff --dry-run
```

применить исправления

```bash
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
```

# Тесты:

    composer install --dev

### run all tests

    /usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests --teamcity

### run personal test for example testPost

    /usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests/Functional/Controller/ApiControllerTest.php --filter "/::testPost( .*)?$/" 

## Thanks

## Done

## License
    PROPRIETARY
   