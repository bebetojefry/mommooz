<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use App\FrontBundle\Entity\Product;
use App\FrontBundle\Form\ProductType;
use App\FrontBundle\Helper\FormHelper;

class ProductController extends Controller
{
    /**
     * @Route("/results", name="product_results", options={"expose"=true})
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.front.datatable.product');
        $datatable->buildDatatable();
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $query->buildQuery();
        
        $query->useQueryCache(true);            // (1)
        $query->useCountQueryCache(true);       // (2)
        $query->useResultCache(true, 60);       // (3)
        $query->useCountResultCache(true, 60);  // (4)
    
        return $query->getResponse();
    }
    
    /**
     * Displays a form to add an existing product entity.
     *
     * @Route("/new", name="product_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $product = new Product();
        $product->setStatus(true);
        $product->setDeliverable(1);
        $form = $this->createForm(new ProductType($dm), $product);
        
        $rootCategory = $dm->getRepository('AppFrontBundle:Category')->find(1);
        $resultTree = array();
        $this->getCatTree($resultTree, $rootCategory);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $product = $form->getData();
                if($product->getDeliverable() != 2){
                    $product->setRegions(new ArrayCollection());
                }
                $product->setStatus(true);
                $dm->persist($product);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'product.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Product:form.html.twig',
            array('form' => $form->createView(), 'treeData' => json_encode($resultTree))
        );

        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    private function getCatTree(&$result, $category, $selCat = null){
        $result[] = array('text' => $category->getCategoryName(), 'id' => $category->getId());
        
        $keys = array_keys($result);
        if($selCat){
            $result[end($keys)]['state'] = array();
            if($category->hasChild($selCat)){
                $result[end($keys)]['state']['expanded'] = 'true'; 
            }
            
            if($category->getId() == $selCat->getId()){
                $result[end($keys)]['state']['selected'] = 'true'; 
            }
        }
        
        if(count($category->getChilds()) > 0){
            $result[end($keys)]['nodes'] = array();
            foreach($category->getChilds() as $cat){
                $this->getCatTree($result[end($keys)]['nodes'], $cat, $selCat);
            }
        }
    }
    
    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ProductType($dm), $product);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $product = $form->getData();
                if($product->getDeliverable() != 2){
                    $product->setRegions(new ArrayCollection());
                }
                $dm->persist($product);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'product.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $rootCategory = $dm->getRepository('AppFrontBundle:Category')->find(1);
        $resultTree = array();
        $this->getCatTree($resultTree, $rootCategory, $product->getCategory());
        
        $keywords = $product->getKeywords()->getValues();
        $keyword_values = array();
        foreach($keywords as $keyword){
            $keyword_values[] = array('id' => $keyword->getId(), 'name' => $keyword->getKeyword());
        }
        
        $regions = $product->getRegions()->getValues();
        $region_values = array();
        foreach($regions as $region){
            $region_values[] = array('id' => $region->getId(), 'name' => $region->getRegionName().' ('.$region->getDistrict()->getName().')');
        }
        
        $body = $this->renderView('AppFrontBundle:Product:form.html.twig',
            array('form' => $form->createView(), 'keyword_values' => json_encode($keyword_values), 'region_values' => json_encode($region_values), 'treeData' => json_encode($resultTree))
        );
        
        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing product entity.
     *
     * @Route("/{id}/delete", name="product_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Product $product)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($product);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'product.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * List Region details with location.
     *
     * @Route("/items/{id}", name="product_items", defaults={"id":0}, options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function itemsAction(Request $request, Product $product = null)
    {
        $itemDatatable = $this->get('app.front.datatable.item');
        $itemDatatable->buildDatatable(array('product' => $product));
        return $this->render('AppFrontBundle:Admin:item.html.twig', array(
            'itemDatatable' => $itemDatatable,
        ));
    }
}
