<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Constructor.
     *
     * @param BookRepository           $bookRepository     Book repository
     * @param CommentsRepository       $commentsRepository Comments repository
     * @param CategoryServiceInterface $categoryService    Category service
     * @param PaginatorInterface       $paginator          Paginator
     */
    public function __construct(BookRepository $bookRepository, CommentsRepository $commentsRepository, CategoryServiceInterface $categoryService, PaginatorInterface $paginator)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->commentsRepository = $commentsRepository;
    }

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
    public function getPaginatedList(int $page, array $filters = [], UserInterface $user = null): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->bookRepository->queryAll($filters, $user),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find one book by id.
     *
     * @param int $id Book id
     *
     * @return Book Book
     */
    public function findOneById(int $id): Book
    {
        return $this->bookRepository->find($id);
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

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     *
     * @throws NonUniqueResultException
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        return $resultFilters;
    }
}
