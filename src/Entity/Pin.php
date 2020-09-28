<?php

namespace App\Entity;

use App\Entity\Traits\Timestamps;
use App\Repository\PinRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Validator;

/**
 * @ORM\Entity(repositoryClass=PinRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Pin
{
    // Timestamps Trait
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Validator\NotBlank(message = "Title can't be empty")
     * @Validator\Length(min=10)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Validator\NotBlank(message = "Description can't be empty")
     * @Validator\Length(min=10)
     */
    private $description;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
