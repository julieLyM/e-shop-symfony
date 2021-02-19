<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/dashboard/product")
 */
class ProductController extends AbstractController
{
    /**
     * Creer un article via un formulaire
     * @Route("/create", name="product_create", methods={"GET|POST"})
     * ex. http://localhost:8000/dashboard/product/create
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, SluggerInterface $slugger): Response
    {

        $product = new Product();
        $product->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());



        $user = $this->getDoctrine()->getRepository(User::class)
            ->findOneByEmail('juju@juju.eshop');

        $product->setUser($user);



        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, [
                'label' => "Ajouter un nom au produit"
            ])
            ->add('price', TextType::class, [
                'label' => "Ajouter un prix"
            ])
            ->add('Category', EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'label' => false,
                ])
            ->add('description', TextareaType::class,[
                'label' => false
            ])
            ->add('image', FileType::class,
                [
                    'label' => "choisir une image"
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => "Ajouter"
                ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue durant le chargement de votre image.');
                }

                $product->setImage($newFilename);

            } // endif image

            # Génération de l'alias(=sluggerinterface)
            $product->setAlias(
                $slugger->slug(
                    $product->getName()
                )
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'felicitation , votre produit est bien ligne');

            return $this->redirectToRoute('default_product',[
                'category' => ($product->getCategory())[0]->getAlias(),
                'productalias' => $product->getAlias(),
                'id' => $product->getId()
            ]);

        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
