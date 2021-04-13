<?php

namespace App\Entity;

use App\Repository\ChequeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ChequeRepository::class)
 * @UniqueEntity(
 *  fields={"num"},
 *  message="Un autre cheque est déjà enregistre avec cette numero, merci de la modifier"
 * )
 */
class Cheque
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $num;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $reqAt;

    /**
     * @ORM\ManyToOne(targetEntity=Banque::class, inversedBy="cheques")
     */
    private $banques;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getReqAt(): ?\DateTimeInterface
    {
        return $this->reqAt;
    }

    public function setReqAt(\DateTimeInterface $reqAt): self
    {
        $this->reqAt = $reqAt;

        return $this;
    }

    public function getBanques(): ?Banque
    {
        return $this->banques;
    }

    public function setBanques(?Banque $banques): self
    {
        $this->banques = $banques;

        return $this;
    }

}
