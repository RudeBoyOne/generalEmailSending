<?php
namespace General\EmailSending\Application\Controller;

use General\EmailSending\Application\Response\Response;
use General\EmailSending\Domain\Repository\ServiceRepository;

class ServiceController
{
    /**
     * Summary of serviceRepository
     * @var ServiceRepository
     */
    private ServiceRepository $serviceRepository;

    public function __construct()
    {
        $this->serviceRepository = new ServiceRepository();
    }

    public function getAll()
    {
        $result = $this->serviceRepository->getAllService();
        Response::success(200, 'Service List', $result);
    }
}
