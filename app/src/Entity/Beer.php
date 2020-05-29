<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BeerRepository")
 * @UniqueEntity(fields="name", message="Name is already taken.")
 */
class Beer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("api.get")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api.get")
     * @Assert\Length(min = 3, max = 255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups("api.get")
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     */
    private $abv;

    /**
     * @ORM\Column(type="integer")
     * @Groups("api.get")
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     */
    private $ibu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brasserie")
     * @Groups("api.get")
     */
    private $brasserie;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("api.get")
     * @Assert\NotBlank
     * @Assert\DateTime(
     *      message="Invalid format of date_create"
     * )
     */
    private $date_create;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("api.get")
     * @Assert\NotBlank
     * @Assert\DateTime(
     *      message="Invalid format of date_update"
     * )
     */
    private $date_update;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAbv(): ?float
    {
        return $this->abv;
    }

    public function setAbv(float $abv): self
    {
        $this->abv = $abv;

        return $this;
    }

    public function getIbu(): ?int
    {
        return $this->ibu;
    }

    public function setIbu(int $ibu): self
    {
        $this->ibu = $ibu;

        return $this;
    }

    public function getBrasserie(): ?Brasserie
    {
        return $this->brasserie;
    }

    public function setBrasserie(?Brasserie $brasserie): self
    {
        $this->brasserie = $brasserie;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }
}
