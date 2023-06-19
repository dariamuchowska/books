<?php
/**
 * Comments service.
 */

namespace App\Service;

use App\Entity\Comments;
use App\Repository\CommentsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * Class CommentsService.
 */
class CommentsService implements CommentsServiceInterface
{
    /**
     * Comments repository.
     */
    private CommentsRepository $commentsRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param CommentsRepository     $commentsRepository Comments repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(CommentsRepository $commentsRepository, PaginatorInterface $paginator)
    {
        $this->commentsRepository = $commentsRepository;
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
            $this->commentsRepository->queryAll(),
            $page,
            CommentsRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Comments $comments Comments entity
     */
    public function save(Comments $comments): void
    {
        if ($comments->getId() == null) {
            $comments->setCreatedAt(new \DateTimeImmutable());
        }

        $this->commentsRepository->save($comments);
    }

    /**
     * Delete entity.
     *
     * @param Comments $comments Comments entity
     */
    public function delete(Comments $comments): void
    {
        $this->commentsRepository->delete($comments);
    }
}