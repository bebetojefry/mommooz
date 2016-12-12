<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Category;

class CategoryController extends Controller
{
    /**
     * @Route("/{id}/{child}", name="category_menu", options={"expose"=true})
     */
    public function indexAction(Category $category, $child = null)
    {
        return $this->render('AppWebBundle:Category:menu.html.twig', array('category' => $category, 'child' => $child));
    }
    
    /**
     * @Route("/page/category/{id}", name="category_page", options={"expose"=true})
     */
    public function pageAction(Category $category)
    {
        
    }
        
}
