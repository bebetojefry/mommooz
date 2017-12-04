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
        $this->get('session')->remove('filter_cat');
        $this->get('session')->remove('filter_brand');
        $this->get('session')->remove('filter_offer');

        return $this->render('AppWebBundle:Category:index.html.twig', array(
            'category' => $category
        ));
    }

    /**
     * @Route("/{id}/filter", name="category_filter", options={"expose"=true})
     */
    public function filterAction(Category $category)
    {
        return new Response($this->renderView('AppWebBundle:Category:filter.html.twig', array(
            'category' => $category
        )));
    }

    /**
     * @Route("/{id}/page/{page}.html", name="category_next_page", options={"expose"=true})
     */
    public function nextPageAction(Request $request, Category $category, $page)
    {
        $cats = array();
        $brands = array();
        $isFilter = $request->isMethod('POST');

        if($request->isMethod('GET')) {
            if ($this->get('session')->get('filter_cat')) {
                $cats = explode(';', $this->get('session')->get('filter_cat'));
            }

            if ($this->get('session')->get('filter_brand')) {
                $brands = explode(';', $this->get('session')->get('filter_brand'));
            }

            if ($this->get('session')->get('filter_offer')) {
                $onOffer = $this->get('session')->get('filter_offer');
            }
        }

        if($request->isMethod('POST')) {
            $onOffer = isset($_POST["on_offer"]);
            foreach ($_POST as $val) {
                $val = explode('_', $val);
                if (count($val) == 2) {
                    switch ($val[0]) {
                        case 'cat':
                            if(!in_array($val[1], $cats)) {
                                $cats[] = $val[1];
                            }
                            break;
                        case 'brand':
                            if(!in_array($val[1], $brands)) {
                                $brands[] = $val[1];
                            }

                            break;
                    }
                }
            }

            $this->get('session')->set('filter_cat', implode(';', $cats));
            $this->get('session')->set('filter_brand', implode(';', $brands));
            $this->get('session')->set('filter_offer', $onOffer);
        }

        return $this->render('AppWebBundle:Category:nextPage.html.twig', array(
            'category' => $category,
            'cats' => $cats,
            'brands' => $brands,
            'onOffer' => $onOffer,
            'cur_page' => $request->query->get('cur_page', 1),
            'isFilter' => $isFilter
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
