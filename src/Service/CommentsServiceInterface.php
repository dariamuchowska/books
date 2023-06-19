<?php
/**
 * Comments service interface.
 */

namespace App\Service;

use App\Entity\Comments;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentsServiceInterface.
 */
interface CommentsServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Comments $comments Comments entity
     */
    public function save(Comments $comments): void;

    /**
     * Delete entity.
     *
     * @param Comments $comments Comments entity
     */
    public function delete(Comments $comments): void;

}