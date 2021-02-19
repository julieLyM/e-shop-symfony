<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * page accueil
     * http://localhost:8000
     * @Route("/", name="default_index", methods={"GET"})

     */
    public function index()
    {
        #recuperer depuis notre model (entité) les articles de la bdd
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
        #return new Response("<h1>Page d'accueil</h1>");
        return $this->render('default/index.html.twig',[
            'products' => $products
        ]);
    }

    /**
     * page category
     * @Route("/{alias}", name="default_category", methods={"GET"})
     */
    public function category( Category $category )
    {
        return $this->render('default/category.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("{category}/{productalias}/{id}.html", name="default_product", methods={"GET"})
     */
    public function product(Product $product)
    {
        return $this->render('default/product.html.twig',[
            'product' =>$product
        ]);
    }

}