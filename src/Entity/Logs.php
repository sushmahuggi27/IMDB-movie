<?php

namespace App\Entity;

use App\Repository\LogsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogsRepository::class)
 */
class Logs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=225)
     */
    private $admin_mail;

    /**
     * @ORM\Column(type="string", length=550)
     */
    private $action;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=225)
     */
    private $method;

    /**
     * @ORM\Column(type="string", length=225)
     */
    private $logdata;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdminMail(): ?string
    {
        return $this->admin_mail;
    }

    public function setAdminMail(string $admin_mail): self
    {
        $this->admin_mail = $admin_mail;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getLogdata(): ?string
    {
        return $this->logdata;
    }

    public function setLogdata(string $logdata): self
    {
        $this->logdata = $logdata;

        return $this;
    }
}