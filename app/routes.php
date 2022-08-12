<?php
declare(strict_types=1);

use App\Application\Actions\Item\CreateItemAction;
use App\Application\Actions\Item\DeleteItemAction;
use App\Application\Actions\Item\ListItemAction;
use App\Application\Actions\Item\UpdateItemAction;
use App\Application\Actions\Item\ViewItemAction;
use App\Application\Actions\Section\CreateSectionAction;
use App\Application\Actions\Section\DeleteSectionAction;
use App\Application\Actions\Section\ListSectionAction;
use App\Application\Actions\Section\UpdateSectionAction;
use App\Application\Actions\Section\ViewSectionAction;
use App\Application\Actions\Status\CreateStatusAction;
use App\Application\Actions\Status\DeleteStatusAction;
use App\Application\Actions\Status\ListStatusAction;
use App\Application\Actions\Status\UpdateStatusAction;
use App\Application\Actions\Status\ViewStatusAction;
use App\Application\Actions\Tab\CreateTabAction as CreateTabAction;
use App\Application\Actions\Tab\DeleteTabAction as DeleteTabAction;
use App\Application\Actions\Tab\UpdateTabAction as UpdateTabAction;
use App\Application\Actions\Tab\ListTabAction as ListTabAction;
use App\Application\Actions\Tab\ViewTabAction as ViewTabAction;
use App\Application\Actions\Ticket\CreateTicketAction as CreateTicketAction;
use App\Application\Actions\Ticket\DeleteTicketAction as DeleteTicketAction;
use App\Application\Actions\Ticket\UpdateTicketAction as UpdateTicketAction;
use App\Application\Actions\Ticket\ListTicketsAction as ListTicketsAction;
use App\Application\Actions\Ticket\ViewTicketAction as ViewTicketAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(['hello' => 'world']));
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->group('/tickets', function (Group $group) {
        $group->post('', CreateTicketAction::class);
        $group->get('', ListTicketsAction::class);
        $group->get('/{id}', ViewTicketAction::class);
        $group->patch('/{id}', UpdateTicketAction::class);
        $group->delete('/{id}', DeleteTicketAction::class);
    });

    $app->group('/tabs', function (Group $group) {
        $group->post('', CreateTabAction::class);
        $group->get('', ListTabAction::class);
        $group->get('/{id}', ViewTabAction::class);
        $group->patch('/{id}', UpdateTabAction::class);
        $group->delete('/{id}', DeleteTabAction::class);
    });

    $app->group('/status', function (Group $group) {
        $group->post('', CreateStatusAction::class);
        $group->get('', ListStatusAction::class);
        $group->get('/{id}', ViewStatusAction::class);
        $group->patch('/{id}', UpdateStatusAction::class);
        $group->delete('/{id}', DeleteStatusAction::class);
    });

    $app->group('/sections', function (Group $group) {
        $group->post('', CreateSectionAction::class);
        $group->get('', ListSectionAction::class);
        $group->get('/{id}', ViewSectionAction::class);
        $group->patch('/{id}', UpdateSectionAction::class);
        $group->delete('/{id}', DeleteSectionAction::class);
    });

    $app->group('/items', function (Group $group) {
        $group->post('', CreateItemAction::class);
        $group->get('', ListItemAction::class);
        $group->get('/{id}', ViewItemAction::class);
        $group->patch('/{id}', UpdateItemAction::class);
        $group->delete('/{id}', DeleteItemAction::class);
    });

};
