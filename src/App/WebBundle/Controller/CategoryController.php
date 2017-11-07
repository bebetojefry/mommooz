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
    
    public function indexAction(Category $category, $child = null)
    {
        if(!$category->getStatus()){
            return new Response('Category not found...', 404);
        }

        return $this->render('AppWebBundle:Category:menu.html.twig', array('category' => $category, 'child' => $child));
    }
    
    /**
     * @Route("/{id}/page", name="category_page", options={"expose"=true})
     */
    public function pageAction(Category $category)
    {       
        return $this->render('AppWebBundle:Category:index.html.twig', array(
            'category' => $category
        ));
    }
    
    public function popularAction(){
        $categories = $this->getDoctrine()->getManager()->getRepository('AppFrontBundle:Category')->findBy(array('popular' => true, 'status' => true), null, 4, 0);
        return $this->render('AppWebBundle:Category:popular.html.twig', array('categories' => $categories));
    }
    
    public function popularMenuAction(){
        $categories = $this->getDoctrine()->getManager()->getRepository('AppFrontBundle:Category')->findBy(array('popular' => true), null, 3, 0);
        return $this->render('AppWebBundle:Category:popular_menu.html.twig', array('categories' => $categories));
    }
    
    public function catSideMenuAction(Category $category, Category $selected)
    {
        return $this->render('AppWebBundle:Category:cat_side_menu.html.twig', array('category' => $category, 'selected' => $selected));
    }
}
