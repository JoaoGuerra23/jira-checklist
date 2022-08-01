<?php
declare(strict_types=1);

use App\Application\Actions\User\AddTicketAction as AddTicketAction;
use App\Application\Actions\User\ListTicketsAction as ListTicketsAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewTicketAction as ViewTicketAction;
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

  /*  $app->get('tickets/4', function (Request $request, Response $response) {
        $data = [
            4 => new \App\Domain\User\Ticket(4, "EX-4444")
        ];
        $arg = json_encode($data);
        $response->getBody()->write($arg);
        return $response;
    });*/
    $app->group('/tickets', function (Group $group) {
        $group->get('', ListTicketsAction::class);
        $group->get('/{id}', ViewTicketAction::class);
        $group->put('/{id}', ListTicketsAction::class);
        $group->delete('/{id}', ListTicketsAction::class);
        //add 4th ticket
        $group->post('/{id}/{ticketCode}', AddTicketAction::class);
    });


};
