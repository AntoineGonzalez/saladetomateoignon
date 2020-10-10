<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
             return $this->redirectToRoute('menus');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }


    /**
     * @Route("/signup", name="app_signup")
     */
    public function signup(Request $request,
                            UserRepository $userRepository,
                            EntityManagerInterface $manager,
                            UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->setFidelityPoint(0);

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, [
                'label' => "Adresse mail",
                'required' => true,
            ])
            ->add('phone', TelType::class, [
                'required' => true,
                'label' => "Téléphone"
            ])
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => "Prénom"
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => "Nom"
            ])
            ->add('address', TextType::class, [
                'label' => "Rue, Lieu-dit"
            ])
            ->add('cp', TextType::class, [
                'label' => "Code postal"
            ])
            ->add('city', TextType::class, [
                'label' => "Ville"
            ])
            ->add('DiscordID', TextType::class, [
                'required' => false,
                'label' => "ID Discord"
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être similaires',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
            ])
            ->add('save', SubmitType::class, ['label' => 'S\'inscrire !'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('menus');
        }

        return $this->render('signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/userIDFromDiscord/{discordTag}", name="userIDFromDiscord")
     */
    public function getUserIdFromDiscordTag(UserRepository $userRepo, $discordTag) {
      $user = $userRepo->findOneBy(["DiscordID" => $discordTag]);

      if($user) {
        $id = $user->getId();
      } else {
        $id = -1;
      }

      return $this->json(['id' => $id]);
    }
}
