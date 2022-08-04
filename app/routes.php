<?php
declare(strict_types=1);

use App\Application\Actions\Ticket\CreateTicketAction as CreateTicketAction;
use App\Application\Actions\Ticket\DeleteTicketAction as DeleteTicketAction;
use App\Application\Actions\Ticket\EditTicketAction as EditTicketAction;
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
        $response->getBody()->write('Hello world!');
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
        $group->patch('/{id}', EditTicketAction::class);
        $group->delete('/{id}', DeleteTicketAction::class);
    });

};
