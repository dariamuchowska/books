<?php
/**
 * Book service interface.
 */

namespace App\Service;

use App\Entity\Book;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface BookServiceInterface.
 */
interface BookServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param array              $filters Filters
     * @param UserInterface|null $user    User
     *
     * @return PaginationInterface<string, mixed> Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, array $filters = [], UserInterface $user = null): PaginationInterface;

    /**
     * Find one book by id.
     *
     * @param int $id Book id
     */
    public function findOneById(int $id): Book;

    /**
     * Save entity.
     *
     * @param Book $book Book entity
     */
    public function save(Book $book): void;

    /**
     * Delete entity.
     *
     * @param Book $book Book entity
     */
    public function delete(Book $book): void;

    /**
     * Can Book be deleted?
     *
     * @param Book $book Book entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Book $book): bool;
}
