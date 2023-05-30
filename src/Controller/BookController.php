<?php
/**
 * Task controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Service\BookService;
use App\Service\BookServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController.
 */
#[Route('/book')]
class BookController extends AbstractController
{
    /**
     * Book service.
     */
    private BookServiceInterface $bookService;

    /**
     * Constructor.
     */
    public function __construct(BookServiceInterface $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'book_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->bookService->getPaginatedList(
            $request->query->getInt('page', 1),
        );

        return $this->render('book/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Book $book Book
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'book_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', ['book' => $book]);
    }
}
