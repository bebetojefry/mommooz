<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FrontBundle\Entity\Product;

class AdminController extends Controller
{
    public function dashboardAction(Request $request){
        $regionDatatable = $this->get('app.front.datatable.region');
        $regionDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:dashboard.html.twig', array(
            'regionDatatable' => $regionDatatable,
        ));
    }
    
    public function regionAction(Request $request){
        $regionDatatable = $this->get('app.front.datatable.region');
        $regionDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:region.html.twig', array(
            'regionDatatable' => $regionDatatable,
        ));
    }
    
    public function categoryAction(Request $request){
        $categoryDatatable = $this->get('app.front.datatable.category');
        $categoryDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:category.html.twig', array(
            'categoryDatatable' => $categoryDatatable,
        ));
    }
    
    public function productAction(Request $request){
        $productDatatable = $this->get('app.front.datatable.product');
        $productDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:product.html.twig', array(
            'productDatatable' => $productDatatable,
        ));
    }
    
    public function brandAction(Request $request){
        $brandDatatable = $this->get('app.front.datatable.brand');
        $brandDatatable->buildDatatable();
        return $this->render('AppFrontBundle:Admin:brand.html.twig', array(
            'brandDatatable' => $brandDatatable,
        ));
    }
}
