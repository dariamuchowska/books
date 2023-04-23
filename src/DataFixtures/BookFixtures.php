<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;

/**
 * Class BookFixtures.
 */
class BookFixtures extends AbstractBaseFixtures
{
    /**
     * Load.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $task = new Book();
            $task->setName($this->faker->words(3, true));
            $task->setBlurb($this->faker->paragraphs(2, true));
            $this->manager->persist($task);
        }
        $this->manager->flush();
    }
}
