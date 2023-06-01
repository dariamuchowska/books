<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Category.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
#[ORM\UniqueConstraint(name: 'uq_categories_title', columns: ['name'])]
#[UniqueEntity(fields: ['name'])]
class Category
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 100)]
    private ?string $name = null;

    /**
     * Slug.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 100)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string|null $name Name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for slug.
     *
     * @return string|null $slug Slug
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Setter for slug.
     *
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }
}
