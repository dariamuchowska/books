<?php
/**
 * Books entity.
 */

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Book.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'books')]
class Book
{
    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="tasks",
     *     fetch="EXTRA_LAZY",
     * )
     * @ORM\JoinTable(name="tasks_tags")
     *
     * @Assert\Type(type="Doctrine\Common\Collections\Collection")
     */
    private $tags;

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
     * Blurb.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $blurb = null;

    /**
     * Category.
     *
     * @var Category
     */
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Getter for ID.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for Blurb.
     *
     * @return string|null
     */
    public function getBlurb(): ?string
    {
        return $this->blurb;
    }

    /**
     * Setter for Blurb.
     *
     * @param string|null $blurb
     */
    public function setBlurb(?string $blurb): void
    {
        $this->blurb = $blurb;
    }

    /**
     * Getter for Category.
     *
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }
}
