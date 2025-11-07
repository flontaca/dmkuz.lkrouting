<?php

namespace Dmkuz\Lkrouting;

use Bitrix\Main\Context;
use Bitrix\Main\UI\Extension;
use Dmkuz\Lkrouting\Service\RoutingService;
use Dmkuz\Lkrouting\Service\StaticService;

class EventHandler
{
    public static function loadStatic()
    {
        $request = Context::getCurrent()->getRequest();
        $requestPage = $request->getRequestedPage();

        if (preg_match('@/company/personal/user/[0-9]+/@i', $requestPage)) {
            $routingService = new RoutingService();
            $request_uri = $_SERVER['REQUEST_URI'];
            $path_only = parse_url($request_uri, PHP_URL_PATH);

            if ($routingService->checkRoute($path_only) != false) {
                $route = $routingService->getRoute($path_only);
                // Подключаем файл и завершаем выполнение
                $filePath = $_SERVER['DOCUMENT_ROOT'] . $route['FILE_PATH'];
                if (file_exists($filePath)) {
                    $staticService = new StaticService();
                    $staticService->showStatic($filePath);
                    exit();
                }
            }
        }
    }

    public static function loadLkLinksExtension()
    {
        $request = Context::getCurrent()->getRequest();
        if ($request->isAjaxRequest()) {
            return;
        }
        $requestPage = $request->getRequestedPage();

        if (preg_match('@/company/personal/user/[0-9]+/@i', $requestPage)) {
            Extension::load("dmkuz.lkrouting.add_menu_item");
        }
    }
}