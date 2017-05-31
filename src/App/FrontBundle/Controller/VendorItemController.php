<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\VendorItem;
use App\FrontBundle\Helper\FormHelper;
use App\FrontBundle\Entity\Vendor;

class VendorItemController extends Controller
{
    /**
     * @Route("/{id}/results", name="vendoritem_results", options={"expose"=true})
     */
    public function indexResultsAction(Vendor $vendor)
    {
        $datatable = $this->get('app.front.datatable.vendorItem');
        $datatable->buildDatatable(array('vendor' => $vendor));
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        $qb = $query->getQuery();
        if($vendor){
            $qb->andWhere("vendor_item.vendor = :v");
            $qb->setParameter('v', $vendor);
        }
        $query->setQuery($qb);
        
        return $query->getResponse();
    }
    
    /**
     * Delete vendorItem entity.
     *
     * @Route("/{id}/delete", name="vendoritem_delete", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function deleteAction(VendorItem $vendorItem)
    {
        $dm = $this->getDoctrine()->getManager();
        $dm->remove($vendorItem);
        $dm->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'vendoritem.msg.removed');
        return new Response(json_encode(array('code' => FormHelper::REFRESH)));
    }
    
    /**
     * Displays category tree to add more items.
     *
     * @Route("/{id}/add_more_category", name="add_vendor_item_category", defaults={"id":0}, options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Vendor $vendor)
    {
        $dm = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            if(isset($_POST['vendor_opt_category'])){
                $category = $dm->getRepository('AppFrontBundle:Category')->find($_POST['vendor_opt_category']);
                if($category){
                    $body = $this->renderView('AppFrontBundle:Vendor:cat_items.html.twig',
                        array('category' => $category, 'vendor' => $vendor)
                    );

                    return new Response(json_encode(array('code' => FormHelper::REFRESH_FORM, 'data' => $body)));
                }
            } else if(isset($_POST['items'])){
                foreach($_POST['items'] as $it){
                    $item = $dm->getRepository('AppFrontBundle:Item')->find($it);
                    if($item){
                        $vendorItem = new VendorItem();
                        $vendorItem->setVendor($vendor);
                        $vendorItem->setItem($item);
                        $vendorItem->setStatus(true);
                        
                        $dm->persist($vendorItem);
                    }
                }
                $dm->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'vendor.msg.item.added');
                return new Response(json_encode(array('code' => FormHelper::REFRESH, 'data' => '')));
            }
        }
        
        $rootCategory = $dm->getRepository('AppFrontBundle:Category')->find(1);
        $resultTree = array();
        $this->getCatTree($resultTree, $rootCategory);
        
        $body = $this->renderView('AppFrontBundle:Vendor:choose_cat.html.twig',
            array('treeData' => json_encode($resultTree), 'vendor' => $vendor)
        );

        return new Response(json_encode(array('code' => FormHelper::FORM, 'data' => $body)));
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
