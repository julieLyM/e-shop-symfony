<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @package App\Controller
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Page Inscription
     * http://localhost:8000/user/register
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @Route("/register", name="user_register", methods={"GET|POST"})

     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        # Création d'un User
        $user = new User();
        $user->setCreatedAt(new \DateTime())
             ->setUpdatedAt(new \DateTime())
             ->setRoles(['ROLE_USER']);

        # Création du Formulaire
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('adresse', TextType::class)
            ->add('zipcode', TextType::class)
            ->add('city', TextType::class)
            ->add('telephone', TextType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Encodage du mot de passe
            $user->setPassword(
                $encoder->encodePassword(
                    $user, $user->getPassword()
                )
            );

            # Insertion dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            # Redirection
            return $this->redirectToRoute('index');

        }

        # Affichage du Formulaire dans la vue
        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}