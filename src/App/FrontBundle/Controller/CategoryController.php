<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Category;
use App\FrontBundle\Form\CategoryType;
use App\FrontBundle\Helper\FormHelper;

class CategoryController extends Controller
{
    /**
     * @Route("/{id}/results", name="category_results", defaults={"id":0}, options={"expose"=true})
     */
    public function indexResultsAction(Category $category = null)
    {
        $datatable = $this->get('app.front.datatable.category');
        $datatable->buildDatatable(array('category' => $category));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();
        $qb = $query->getQuery();
        if($category){
            $qb->andWhere("category.parent = :c");
            $qb->setParameter('c', $category);
        }
        $query->setQuery($qb);
        return $query->getResponse(false);
    }
    
    /**
     * Displays a form to add an existing location entity.
     *
     * @Route("/{id}/new", name="category_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Category $parent)
    {
        $dm = $this->getDoctrine()->getManager();
        $category = new Category();
        $category->setStatus(true);
        $category->setParent($parent);
        $form = $this->createForm(new CategoryType($dm), $category);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $category = $form->getData();
                $category->setStatus(true);
                $dm->persist($category);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'category.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Category:form.html.twig',
            array('form' => $form->createView())
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing location entity.
     *
     * @Route("/{id}/edit", name="category_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CategoryType($dm), $category);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $category = $form->getData();
                $dm->persist($category);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'category.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $keywords = $category->getKeywords()->getValues();
        $keyword_values = array();
        foreach($keywords as $keyword){
            $keyword_values[] = array('id' => $keyword->getId(), 'name' => $keyword->getKeyword());
        }
        
        $body = $this->renderView('AppFrontBundle:Category:form.html.twig',
            array('form' => $form->createView(), 'keyword_values' => json_encode($keyword_values))
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing location entity.
     *
     * @Route("/{id}/delete", name="category_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Category $category)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($category);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'category.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * List Region details with location.
     *
     * @Route("/{id}/details", name="category_show", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function detailAction(Request $request, Category $category)
    {
        $dm = $this->getDoctrine()->getManager();        
        $code = FormHelper::FORM;
        
        $categoryDatatable = $this->get('app.front.datatable.category');
        $categoryDatatable->buildDatatable(array('category' => $category));
        return $this->render('AppFrontBundle:Admin:category.html.twig', array(
            'categoryDatatable' => $categoryDatatable,
        ));
    }
}
