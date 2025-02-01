<?php
namespace General\EmailSending\Domain\Repository;

use General\EmailSending\Domain\Database\Connection;
use General\EmailSending\Domain\Exception\ErroCreatingEntityException;
use General\EmailSending\Domain\Model\Service;
use PDO;
use PDOException;

class ServiceRepository
{
    /**
     * Summary of connection
     * @var PDO
     */
    private PDO $connection;
    /**
     * Summary of table
     * @var string
     */
    private string $table = 'service';

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    /**
     * Summary of createService
     * @param \General\EmailSending\Domain\Model\Service $service
     * @throws \General\EmailSending\Domain\Exception\ErroCreatingEntityException
     * @return bool
     */
    public function createService(Service $service): Service
    {
        try {
            $existingService = $this->getServiceByName($service->getName());

            if (!$existingService) {
                $name = $service->getName();

                $query = "INSERT INTO $this->table (name) VALUES (:name)";

                $stmt = $this->connection->prepare($query);

                $stmt->bindParam(':name', $name);
                $stmt->execute();

                $newServiceId = $this->connection->lastInsertId();

                $newService = $this->getServiceById($newServiceId);

                return $newService;
            }
            $service = $this->assemblerService($existingService);
            return $service;
        } catch (PDOException $e) {
            error_log('ServiceRepository - Falha na conexão: ' . $e->getMessage() . "\n", 3, './error.log');
            throw new ErroCreatingEntityException('Service ' .$e->getMessage());
        }
    }

    /**
     * Summary of getAllService
     * @return array
     */
    public function getAllService(): array
    {
        $query = "SELECT * FROM $this->table";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $services = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $services;
    }

    /**
     * Summary of getLeadByName
     * @param string $name
     */
    public function getServiceByName(string $name): ?object
    {
        $query = "SELECT * FROM $this->table WHERE name = :name";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return $result ?: null;
    }

    public function getServiceById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);

        $service = $this->assemblerService($result);

        return $service;
    }

    private function assemblerService(object $data): Service
    {
        $service = new Service();
        $service->setId($data->id)
            ->setName($data->name);
        return $service;
    }

}
