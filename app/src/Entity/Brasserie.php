<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrasserieRepository")
 * @UniqueEntity(fields="name", message="Name is already taken.")
 */
class Brasserie
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
     * @Assert\NotBlank
     * @Assert\Length(min = 3, max = 255)
     * @Groups("api.get")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min = 4)
     * @Groups("api.get")
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z _]+$/",
     *      message="City name must contain only letters spaces or _"
     * )
     * @Groups("api.get")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(min = 3)
     * @Groups("api.get")
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min = 2)
     * @Groups("api.get")
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\DateTime(
     *      message="Invalid format of date_create"
     * )
     * @Groups("api.get")
     */
    private $date_create;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\DateTime(
     *      message="Invalid format of date_update"
     * )
     * @Groups("api.get")
     */
    private $date_update;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Beer", mappedBy="brasserie")
     */
    private $beers;

    public function __contruct()
    {
        $this->beers = new ArrayCollection();
    }

    public function setForeignKeyAsNull(EntityManagerInterface $em): void
    {
        foreach ($this->beers as $beer) {
            $beer->setBrasserie(NULL);
        }
    }

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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

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
