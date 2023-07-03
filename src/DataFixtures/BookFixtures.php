<?php
/**
 * Book Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class BookFixtures.
 */
class BookFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }
        $this->createMany(100, 'books', function (int $i) {
            $book = new Book();
            $book->setName($this->faker->sentence());
            $book->setBlurb($this->faker->paragraphs(2, true));
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $book->setCategory($category);

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $book->setAuthor($author);

            return $book;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
