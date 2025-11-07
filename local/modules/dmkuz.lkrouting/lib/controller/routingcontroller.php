<?php
namespace Dmkuz\Lkrouting\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Dmkuz\Lkrouting\Service\RoutingService;

class RoutingController extends Controller
{
    protected function getDefaultPreFilters()
    {
        return [
            new ActionFilter\HttpMethod(['POST', 'GET', 'DELETE']),
            new ActionFilter\Authentication(),
            new ActionFilter\Csrf()
        ];
    }

    public function configureActions()
    {
        return [
            'list' => [
                'prefilters' => []
            ]
        ];
    }

    public function createAction(array $routeData)
    {
        try {
            $routingService = new RoutingService();
            $result = $routingService->createRoute($routeData);

            return ['success' => true, 'data' => $result];
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage()));
            return ['success' => false, 'errors' => $this->getErrors()];
        }
    }

    public function updateAction(int $routeId, array $routeData)
    {
        try {
            $routingService = new RoutingService();
            $result = $routingService->updateRoute($routeId, $routeData);
            return ['success' => true, 'data' => $result];
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage()));
            return ['success' => false, 'errors' => $this->getErrors()];
        }
    }

    public function listAction()
    {
        $routingService = new RoutingService();
        return $routingService->getRoutes();
    }

    public function deleteAction(int $routeId)
    {
        try {
            $routingService = new RoutingService();
            $result = $routingService->deleteRoute($routeId);

            return ['success' => true, 'deleted' => $result];
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage()));
            return ['success' => false, 'errors' => $this->getErrors()];
        }
    }
}