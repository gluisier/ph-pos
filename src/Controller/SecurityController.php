<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\NotBlank;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route("/reset-password/{token}", name: "reset_password", methods: ["GET", "POST"])]
    public function resetPassword(
        Request $request,
        string $token,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $repository = $entityManager->getRepository(User::class);
        if (!($user = $repository->findOneByToken($token))) {
            throw $this->createNotFoundException();
        }

        $formBuilder = $this->createFormBuilder();
        $formBuilder
            ->add('password', \Symfony\Component\Form\Extension\Core\Type\RepeatedType::class, [
                'first_options' =>  [
                    'label' => 'app.fields.user.plainPassword.first.label',
                ],
                'second_options' =>  [
                    'label' => 'app.fields.user.plainPassword.second.label',
                ],
                'type' => \Symfony\Component\Form\Extension\Core\Type\PasswordType::class,
                'constraints' => [
                    new NotBlank(message: 'user.plain_password.blank'),
                ],
            ]);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setPassword($userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                ))
                ->setToken(md5($user->getName() . date('c')));
            $entityManager->flush();

            $this->addFlash(
                'success',
                new TranslatableMessage(
                    'app.flash.success.reset_password.done'
                )
            );
            return $this->redirectToRoute('login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
