<?php
namespace General\EmailSending;

use Dotenv\Dotenv;
use General\EmailSending\Application\Controller\LeadController;
use General\EmailSending\Application\Controller\ServiceController;
use General\EmailSending\Application\Response\Response;

require_once "./vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch ($uri) {
    case '/leads':
        $leadController = new LeadController();

        switch ($method) {
            case 'POST':

                $data = json_decode(file_get_contents('php://input'));
                $leadController->create($data);

                break;
            case 'GET':

                $leadController->getAll();

                break;
        }
        break;
    case '/services':

        $serviceController = new ServiceController();
        $serviceController->getAll();

        break;
    default:

        Response::notFound(404, 'Rota não encontrada');

        break;
}

