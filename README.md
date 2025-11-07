# Модуль dmkuz.lkrouting для Битрикс
Модуль для управления маршрутизацией и создания пользовательских ссылок в лк Б24
При создании маршрута url - уникальный и валидируется.
Для каждого url создаётся ссылка в личном профиле сотрудника. По ссылке доступны статические файлы.
Указание пути до файлов только внутри сайта. Так как используется константа site_dir.

Создание маршрутов
```php
use Dmkuz\Lkrouting\Service\RoutingService;
$service = new RoutingService();

$service->createRoute([
    'TITLE' => 'HTML', 
    'URL' => 'contact', 
    'FILE_PATH' => '/test/index.html'
]);

$service->createRoute([
    'TITLE' => 'HTML2', 
    'URL' => 'html/2', 
    'FILE_PATH' => '/test/index.html'
]);

$service->createRoute([
    'TITLE' => 'Изображение', 
    'URL' => 'image', 
    'FILE_PATH' => '/test/Example.png'
]);

$service->createRoute([
    'TITLE' => 'Видео', 
    'URL' => 'video', 
    'FILE_PATH' => '/test/sample-5s.mp4'
]);

// Обновление маршрута (ID = 6)
$service->updateRoute(6, [
    'TITLE' => 'TEST'
]);

// Удаление маршрута (ID = 7)
$service->deleteRoute(7);
```

Пример обращения к контроллеру через js
Получение списка маршрутов через JavaScript
```js
// Получение списка ссылок
BX.ajax.runAction('dmkuz:lkrouting.routingcontroller.list', {
}).then(function(response) {
    const links = response.data;
}).catch(function(response) {
    // console.error('Ошибка:', response.errors);
});
```
