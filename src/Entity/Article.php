<?php
namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: "Le nom doit avoir au moins 5 caractères",
        maxMessage: "Le nom ne peut pas dépasser 50 caractères"
    )]
    private ?string $nom = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 0)]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide")]
    #[Assert\NotEqualTo(value: 0, message: "Le prix ne peut pas être 0")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    private ?string $prix = null;

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrix(): ?string { return $this->prix; }
    public function setPrix(string $prix): static
    {
        $this->prix = $prix;
        return $this;
    }
}
