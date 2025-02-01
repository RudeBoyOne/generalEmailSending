<?php
namespace General\EmailSending\Domain\Repository;

use General\EmailSending\Domain\Database\Connection;
use General\EmailSending\Domain\Exception\ErroCreatingEntityException;
use General\EmailSending\Domain\Model\Lead;
use PDO;
use PDOException;

class LeadRepository
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
    private string $table = 'lead';

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    /**
     * Summary of createLead
     * @param \General\EmailSending\Domain\Model\Lead $lead
     * @return bool
     */
    public function createLead(Lead $lead): bool
    {
        try {
            $name = $lead->getName();
            $email = $lead->getEmail();
            $service = $lead->getService()->getId();

            $query = "INSERT INTO $this->table (name, email, service_id) VALUES (:name, :email, :service_id)";

            $stmt = $this->connection->prepare($query);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':service_id', $service);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            error_log('LeadRepository - Falha na conexão: ' . $e->getMessage() . "\n", 3, './error.log');
            throw new ErroCreatingEntityException('Lead: ' . $e->getMessage());
        }
    }

    /**
     * Summary of getAllLeads
     * @return array
     */
    public function getAllLeads(): array
    {
        $query = "SELECT * FROM $this->table";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $leads = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $leads;
    }
}
