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
     * @Route("/{id}", name="category_menu", options={"expose"=true})
     */
    public function indexAction(Category $category)
    {
        return $this->render('AppWebBundle:Category:menu.html.twig', array('category' => $category));
    }
    
    /**
     * @Route("/menu/{id}", name="category_menu_render", options={"expose"=true})
     */
    public function menuRenderAction(Category $category)
    {
        return $this->render('AppWebBundle:Category:menu.html.twig', array('category' => $category));
    }
}
