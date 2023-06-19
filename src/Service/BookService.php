<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CommentsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * Class BookService.
 */
class BookService implements BookServiceInterface
{
    /**
     * Book repository.
     */
    private BookRepository $bookRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param BookRepository     $bookRepository Book repository
     * @param CommentsRepository $commentsRepository Comments repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(BookRepository $bookRepository, CommentsRepository $commentsRepository, PaginatorInterface $paginator)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;

        $this->commentsRepository = $commentsRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->bookRepository->queryAll(),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Book $book Book entity
     */
    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    /**
     * Delete entity.
     *
     * @param Book $book Book entity
     */
    public function delete(Book $book): void
    {
        $this->bookRepository->delete($book);
    }

    /**
     * Can Book be deleted?
     *
     * @param Book $book Book entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Book $book): bool
    {
        try {
            $result = $this->commentsRepository->countByBook($book);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

}