<?php
namespace General\EmailSending\Domain\Model;

use General\EmailSending\Domain\Model\Service;

class Lead
{
    /**
     * Summary of name
     * @var string
     */
    private string $name;
    /**
     * Summary of email
     * @var string
     */
    private string $email;
    /**
     * Summary of service
     * @var Service
     */
    private Service $service;



    /**
     * Summary of name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Summary of name
     * @param string $name Summary of name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Summary of email
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Summary of email
     * @param string $email Summary of email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Summary of service
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }

    /**
     * Summary of service
     * @param Service $service Summary of service
     * @return self
     */
    public function setService(Service $service): self
    {
        $this->service = $service;
        return $this;
    }
}
