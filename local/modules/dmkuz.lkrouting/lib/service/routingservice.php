<?php

namespace Dmkuz\Lkrouting\Service;

use Dmkuz\Lkrouting\Model\RouteTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime;

class RoutingService
{
    public function checkRoute($url)
    {
        $urlPart = $this->getUrlpart($url);

        $routeDb = RouteTable::getList([
            'filter' => [
                '=URL' => $urlPart
            ],
            'select' => [
                'ID'
            ],
            'limit' => 1,
        ]);

        if ($arRoute = $routeDb->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    public function getRoute($url)
    {
        $urlPart = $this->getUrlpart($url);

        $routeDb = RouteTable::getList([
            'filter' => [
                '=URL' => $urlPart
            ],
            'select' => [
                '*'
            ],
            'limit' => 1,
        ]);

        if ($arRoute = $routeDb->fetch()) {
            return $arRoute;
        } else {
            return false;
        }
    }

    public function getUrlPart(string $url)
    {
        $profileUrlTemplate = $this->getProfileTemplate();
        $profilePattern = $this->getTemplatePattern($profileUrlTemplate);

        $urlPart = preg_replace($profilePattern, '', $url);
        $urlPart = rtrim($urlPart, '/');

        return $urlPart;
    }

    public function getTemplatePattern(string $url)
    {
        $url = '#' . $url . '#';
        $url = str_replace('#USER_ID#', '\d+', $url);

        return $url;
    }

    public function getProfileTemplate(string $siteId = 's1')
    {
        $result = Option::get('intranet', 'path_user', '', 's1');

        return $result;
    }

    public function createRoute(array $routeData)
    {
        $result = RouteTable::add($routeData);
        if ($result->isSuccess()) {
            return $result->getId();
        } else {
            return $result->getErrorMessages();
        }
    }

    public function updateRoute(int $id, array $routeData)
    {
        // Устанавливаем текущее время для CREATED_AT и UPDATED_AT
        $currentTime = new DateTime();
        $routeData['UPDATED_AT'] = $currentTime;

        $result = RouteTable::update($id, $routeData);
        if (!$result->isSuccess()) {
            return $result->getErrorMessages();
        }

    }

    public function getRoutes()
    {
        $routeDb = RouteTable::getList([
            'select' => [
                '*'
            ],
            'limit' => 0,
            'offset' => 0,
            'order' => [
                'ID' => 'ASC',
            ],
        ]);

        while ($arRoute = $routeDb->fetch()) {
            $arRes[] = $arRoute;
        }

        return $arRes;
    }

    public function deleteRoute(int $id)
    {
        $result = RouteTable::delete($id);
        if (!$result->isSuccess()) {
            return $result->getErrorMessages();
        }
    }
}