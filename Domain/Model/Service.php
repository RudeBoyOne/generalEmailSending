<?php
namespace General\EmailSending\Domain\Model;

class Service
{
    /**
     * Summary of id
     * @var int
     */
    private int $id;
    /**
     * Summary of name
     * @var string
     */
    private string $name;

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
     * Summary of id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Summary of id
     * @param int $id Summary of id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
