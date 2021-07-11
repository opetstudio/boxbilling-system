<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['db']['host']   = getenv('DB_HOST') ?: '127.0.0.1';
$config['db']['user']   = getenv('DB_USER') ?: 'root';
$config['db']['pass']   = getenv('DB_PASS') ?: 'root';
$config['db']['dbname'] = getenv('DB_NAME') ?: 'defaultDb';


$app = new \Slim\App(["settings" => $config]);
$container = $app->getContainer();

$container['view'] = new \Slim\Views\PhpRenderer("../templates/");

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
// tickets module
$app->get('/tickets', function (Request $request, Response $response) {
    $this->logger->addInfo("Ticket list");
    $mapper = new TicketMapper($this->db);
    $tickets = $mapper->getTickets();

    $response = $this->view->render($response, "tickets.phtml", ["tickets" => $tickets, "router" => $this->router]);
    return $response;
});

$app->get('/ticket/new', function (Request $request, Response $response) {
    $component_mapper = new ComponentMapper($this->db);
    $components = $component_mapper->getComponents();
    $response = $this->view->render($response, "ticketadd.phtml", ["components" => $components]);
    return $response;
});

$app->post('/ticket/new', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $ticket_data = [];
    $ticket_data['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $ticket_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // work out the component
    $component_id = (int)$data['component'];
    $component_mapper = new ComponentMapper($this->db);
    $component = $component_mapper->getComponentById($component_id);
    $ticket_data['component'] = $component->getName();

    $ticket = new TicketEntity($ticket_data);
    $ticket_mapper = new TicketMapper($this->db);
    $ticket_mapper->save($ticket);

    $response = $response->withRedirect("/tickets");
    return $response;
});

$app->get('/ticket/{id}', function (Request $request, Response $response, $args) {
    $ticket_id = (int)$args['id'];
    $mapper = new TicketMapper($this->db);
    $ticket = $mapper->getTicketById($ticket_id);

    $response = $this->view->render($response, "ticketdetail.phtml", ["ticket" => $ticket]);
    return $response;
})->setName('ticket-detail');

// prices module
$app->get('/prices', function (Request $request, Response $response) {
    $this->logger->addInfo("prices list");
    $mapper = new PriceMapper($this->db);
    $prices = $mapper->getPrices();

    $response = $this->view->render($response, "prices.phtml", ["prices" => $prices, "router" => $this->router]);
    return $response;
});
$app->get('/price/new', function (Request $request, Response $response) {
    $response = $this->view->render($response, "priceadd.phtml");
    return $response;
});
$app->post('/price/new', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $price_data = [];
    $price_data['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $price_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price_data['price'] = filter_var($data['price'], FILTER_SANITIZE_STRING);

    $price = new PriceEntity($price_data);
    $price_mapper = new PriceMapper($this->db);
    $price_mapper->save($price);

    $response = $response->withRedirect("/prices");
    return $response;
});
$app->get('/price/{id}', function (Request $request, Response $response, $args) {
    $price_id = (int)$args['id'];
    $mapper = new PriceMapper($this->db);
    $price = $mapper->getPriceById($price_id);

    $response = $this->view->render($response, "pricedetail.phtml", ["price" => $price]);
    return $response;
})->setName('price-detail');

$app->run();
