<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Product;
use App\FrontBundle\Entity\Item;
use App\FrontBundle\Form\ItemType;
use App\FrontBundle\Helper\FormHelper;
use Doctrine\Common\Collections\ArrayCollection;
use App\FrontBundle\Entity\Brand;

class ItemController extends Controller
{
    /**
     * @Route("/{id}/results", name="item_results", defaults={"id":0}, options={"expose"=true})
     */
    public function indexResultsAction(Request $request, Product $product = null)
    {
        $dm = $this->getDoctrine()->getManager();
        $datatable = $this->get('app.front.datatable.item');
        $datatable->buildDatatable(array('product' => $product));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $qb = $query->getQuery();
        if($product){
            $qb->andWhere("item.product = :p");
            $qb->setParameter('p', $product);
        }
        
        if($brandId = $request->query->get('brand')){
            $brand = $dm->getRepository('AppFrontBundle:Brand')->find($brandId);
            if($brand instanceof Brand) {
                $qb->andWhere("item.brand = :b");
                $qb->setParameter('b', $brand);
            }
        }
        
        $query->setQuery($qb);
        return $query->getResponse();
    }
    
    /**
     * Get products for the given category.
     *
     * @Route("/products", name="item_products")
     * @Method({"GET", "POST"})
     */
    public function productsAction(Request $request)
    {
        $dm = $this->getDoctrine()->getManager();
        $category = null;
        if($request->query->get('cat')){
            $category = $dm->getRepository('AppFrontBundle:Category')->find($request->query->get('cat'));
        }
        
        if($category){
            $products = $category->getAllProducts();
        } else {
            $products = $dm->getRepository('AppFrontBundle:Product')->findAll();
        }
        
        return $this->render('AppFrontBundle:Item:products.html.twig',
            array('products' => $products)
        );
    }
    
    /**
     * Displays a form to add a new item entity.
     *
     * @Route("/{id}/new", name="item_new", defaults={"id":0}, options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Product $product = null)
    {
        $dm = $this->getDoctrine()->getManager();
        $item = new Item();
        $item->setStatus(true);
        $item->setProduct($product);
        $form = $this->createForm(new ItemType($dm), $item);
        
        $rootCategory = $dm->getRepository('AppFrontBundle:Category')->find(1);
        $resultTree = array();
        $this->getCatTree($resultTree, $rootCategory);
        
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $item = $form->getData();
                foreach ($item->getVariants() as $variant){
                    $variant->setItem($item);
                }
                $item->setStatus(true);
                $dm->persist($item);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'item.msg.created');
                $code = FormHelper::REFRESH;
            } else {
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $body = $this->renderView('AppFrontBundle:Item:form.html.twig',
            array('form' => $form->createView(), 'treeData' => json_encode($resultTree))
        );

        return new Response(json_encode(array('code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to edit an existing item entity.
     *
     * @Route("/{id}/edit", name="item_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Item $item)
    {
        $dm = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ItemType($dm), $item);
        $error = '';
        $code = FormHelper::FORM;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $item = $form->getData();
                foreach ($item->getVariants() as $variant){
                    $variant->setItem($item);
                }
                $dm->persist($item);
                $dm->flush();
                $this->get('session')->getFlashBag()->add('success', 'item.msg.updated');
                $code = FormHelper::REFRESH;
            } else {
                $error = $form->getErrorsAsString();
                $code = FormHelper::REFRESH_FORM;
            }
        }
        
        $rootCategory = $dm->getRepository('AppFrontBundle:Category')->find(1);
        $resultTree = array();
        $this->getCatTree($resultTree, $rootCategory, $item->getProduct()->getCategory());
        
        $keywords = $item->getKeywords()->getValues();
        $keyword_values = array();
        foreach($keywords as $keyword){
            $keyword_values[] = array('id' => $keyword->getId(), 'name' => $keyword->getKeyword());
        }
        
        $offers = $item->getOffers()->getValues();
        $offer_values = array();
        foreach($offers as $offer){
            $offer_values[] = array('id' => $offer->getId(), 'name' => $offer->getName());
        }
        
        $categories = $item->getCategories()->getValues();
        $category_values = array();
        foreach($categories as $category){
            $category_values[] = array('id' => $category->getId(), 'name' => $category->getCategoryName());
        }
        
        $body = $this->renderView('AppFrontBundle:Item:form.html.twig',
            array('form' => $form->createView(), 'keyword_values' => json_encode($keyword_values), 'offer_values' => json_encode($offer_values), 'category_values' => json_encode($category_values), 'treeData' => json_encode($resultTree))
        );
        
        return new Response(json_encode(array('error' => $error, 'code' => $code, 'data' => $body)));
    }
    
    /**
     * Displays a form to delete an existing item entity.
     *
     * @Route("/{id}/delete", name="item_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Item $item)
    {
        $dm = $this->getDoctrine()->getManager();
        
        $entries = $dm->getRepository('AppFrontBundle:StockEntry')->findByItem($item);
        
        if(count($entries) > 0){
            $this->get('session')->getFlashBag()->add('error', 'You can\'t delete this item, since there is around '.count($entries).' stock entries associated to this item.');
        } else {
            $dm->remove($item);
            $dm->flush();
            $this->get('session')->getFlashBag()->add('success', 'item.msg.removed');
        }
        
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
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
}
