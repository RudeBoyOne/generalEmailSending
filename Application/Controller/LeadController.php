<?php
namespace General\EmailSending\Application\Controller;

use General\EmailSending\Application\Response\Response;
use General\EmailSending\Application\Service\EmailSenderService;
use General\EmailSending\Application\Util\CryptoManagerUtil;
use General\EmailSending\Domain\Exception\ErroCreatingEntityException;
use General\EmailSending\Domain\Model\Lead;
use General\EmailSending\Domain\Model\Service;
use General\EmailSending\Domain\Repository\LeadRepository;
use General\EmailSending\Domain\Repository\ServiceRepository;
use PDOException;
use PHPMailer\PHPMailer\Exception;

class LeadController
{
    /*
     * Summary of leadRepository
     * @var LeadRepository
     */
    private LeadRepository $leadRepository;
    /**
     * Summary of serviceRepository
     * @var ServiceRepository
     */
    private ServiceRepository $serviceRepository;

    public function __construct()
    {
        $this->leadRepository = new LeadRepository();
        $this->serviceRepository = new ServiceRepository();
    }

    public function create(object $data)
    {
        try {
            $lead = $this->assemblerLead($data);
            $result = $this->leadRepository->createLead($lead);

            try {
                $subject = 'mensagem do ' . $data->service->name;
                EmailSenderService::sendEmail($subject, $data->name, $data->email, $data->message, $data->service->name);
            } catch (Exception $e) {
                error_log('Erro ao tentar enviar o e-mail: ' . $e->getMessage() . "\n", 3, './error.log');
                Response::error(400, 'Erro ao tentar enviar o e-mail: ' . $e->getMessage());
            }

            Response::success(201, 'Lead criado com sucesso', $result);
        } catch (PDOException $e) {
            error_log('LeadController - Falha na conexão: ' . $e->getMessage() . "\n", 3, './error.log');
            Response::error(500, $e->getMessage());
        } catch (ErroCreatingEntityException $e) {
            error_log('LeadController - Erro ao criar entidade Lead: ' . $e->getMessage() . "\n", 3, './error.log');
            Response::error(500, $e->getMessage());
        }

    }

    public function getAll()
    {
        $result = $this->leadRepository->getAllLeads();
        Response::success(200, 'Lead List', $result);
    }

    private function assemblerLead(object $data): Lead
    {
        try {
            $service = new Service();
            $encryptedServiceName = CryptoManagerUtil::encryptData($data->service->name);
            $service->setName($encryptedServiceName);
            $result = $this->serviceRepository->createService($service);
        } catch (PDOException $e) {
            error_log('LeadController - Falha na conexão: ' . $e->getMessage() . "\n", 3, './error.log');
            Response::error(500, $e->getMessage());
        } catch (ErroCreatingEntityException $e) {
            error_log('LeadController - Erro ao criar entidade Lead: ' . $e->getMessage() . "\n", 3, './error.log');
            Response::error(500, $e->getMessage());
        }

        $encryptedLeadName = CryptoManagerUtil::encryptData($data->name);
        $encryptedLeadEmail = CryptoManagerUtil::encryptData($data->email);

        $lead = new Lead();
        $lead->setName($encryptedLeadName)
            ->setEmail($encryptedLeadEmail)
            ->setService($result);

        return $lead;
    }
}

