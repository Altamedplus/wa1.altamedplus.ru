Подгрузка библиотек

```
cd ./cabinet && npm install && composer install
```

Обновление библиотек

```
cd ./cabinet && npm install && composer update
```

Сборка проекта

```
npm run dev
```

Подтянуть изменения

```
git pull
```

Закомитить свои изменения

```
git add . && git commit -m "Ваше сообщение"
```

Отправить свои измениния

```
git push
```

Запустить локальный сервер

```
cd ./cabinet && php pet serve
```

## Тестирование Модулей в корне cd ./cabinet

Запустит тестирование всех модулей проекта 

```
composer test 
```

Тестирование одно модуля

```
composer test:filter [modue]
```
