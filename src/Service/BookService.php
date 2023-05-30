<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Repository\BookRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

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
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(BookRepository $bookRepository, PaginatorInterface $paginator)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
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
}
