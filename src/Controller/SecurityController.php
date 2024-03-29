<?php
/**
 * Security controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordChangeType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
    /**
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param UserService         $userService User service
     * @param TranslatorInterface $translator  Translator
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Login action.
     *
     * @param AuthenticationUtils $authenticationUtils Authentication Utils
     *
     * @return Response HTTP Response
     */
    #[Route(
        path: '/login',
        name: 'app_login'
    )]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Logout action.
     */
    #[Route(
        path: '/logout',
        name: 'app_logout'
    )]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Password change action.
     *
     * @param Request                     $request        HTTP request
     * @param User                        $user           User entity
     * @param UserService                 $userService    User service
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/user/{id}/password_change',
        name: 'app_change-password',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    public function passwordChange(Request $request, User $user, UserService $userService, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(PasswordChangeType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );

            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.pass_changed_successfully')
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'security/passwordchange.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Register action.
     *
     * @param Request                     $request        HTTP Request
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     * @param UserRepository              $userRepository User repository
     *
     * @return Response HTTP Response
     */
    #[Route(
        '/register',
        name: 'app_register',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setRoles([User::ROLE_USER]);
            $userRepository->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.registered_successfully')
            );

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'security/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
