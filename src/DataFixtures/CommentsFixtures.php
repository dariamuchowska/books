<?php
/**
 * Comments fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Comments;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class CommentsFixtures.
 */
class CommentsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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
        $this->createMany(100, 'comments', function (int $i) {
            $comments = new Comments();
            $comments->setNick($this->faker->unique()->word());
            $comments->setContent($this->faker->paragraphs(3, true));
            $comments->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            /** @var Book $book */
            $book = $this->getRandomReference('books');
            $comments->setBook($book);

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $comments->setAuthor($author);

            return $comments;
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
        return [BookFixtures::class, UserFixtures::class];
    }
}
