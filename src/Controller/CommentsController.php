<?php
/**
 * Comments controller.
 */

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\User;
use App\Entity\Book;
use App\Form\CommentsType;
use App\Service\CommentsServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommentsController.
 */
#[Route('/comments')]
class CommentsController extends AbstractController
{
    /**
     * Comments service.
     */
    private CommentsServiceInterface $commentsService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param CommentsServiceInterface $commentsService Comments service
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(CommentsServiceInterface $commentsService, TranslatorInterface $translator)
    {
        $this->commentsService = $commentsService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'comments_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->commentsService->getPaginatedList(
            $request->query->getInt('page', 1),
        );

        return $this->render('comments/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Comments $comments Comments entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'comments_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Comments $comments): Response
    {
        return $this->render(
            'comments/show.html.twig',
            ['comments' => $comments]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'comments_create',
        methods: 'GET|POST'
    )]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Book $book */
        $book = $this->getBook();
        $comments = new Comments();
        $comments->setAuthor($user);
        $comments->setBook($book);
        $form = $this->createForm(
            CommentsType::class,
            $comments,
            ['action' => $this->generateUrl('comments_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentsService->save($comments);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('comments_index');
        }

        return $this->render(
            'comments/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Comments    $comments    Comments entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/edit',
        name: 'comments_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    #[IsGranted('EDIT', subject: 'comments')]
    public function edit(Request $request, Comments $comments): Response
    {
        $form = $this->createForm(
            CommentsType::class,
            $comments,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('comments_edit', ['id' => $comments->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentsService->save($comments);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('comments_index');
        }

        return $this->render(
            'comments/edit.html.twig',
            [
                'form' => $form->createView(),
                'comments' => $comments,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comments    $comments    Comments entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/delete',
        name: 'comments_delete',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|DELETE'
    )]
    #[IsGranted('DELETE', subject: 'comments')]
    public function delete(Request $request, Comments $comments): Response
    {
        $form = $this->createForm(
            FormType::class,
            $comments,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('comments_delete', ['id' => $comments->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentsService->delete($comments);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('comments_index');
        }

        return $this->render(
            'comments/delete.html.twig',
            [
                'form' => $form->createView(),
                'comments' => $comments,
            ]
        );
    }
}