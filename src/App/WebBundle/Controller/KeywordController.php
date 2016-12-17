<?php

namespace App\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\FrontBundle\Entity\Vendor;

class KeywordController extends Controller
{
    /**
     * @Route("/autocomplete", name="keyword_web_autocomplete")
     */
    public function autocompleteAction(Request $request)
    {
        $q = $request->query->get('q');
        $repo = $this->getDoctrine()->getManager()->getRepository('AppFrontBundle:Keyword');
        $keywords = $repo->createQueryBuilder('k')
                ->where('k.keyword LIKE :q')
                ->setParameter('q', '%'.$q.'%')
                ->getQuery()
                ->getResult();
        
        $result = array();
        foreach($keywords as $keyword){
            $result[] = $keyword->getKeyword();
        }
        
        return new JsonResponse($result);         
    }
    
    /**
     * @Route("/search", name="keyword_web_search")
     */
    public function searchAction(Request $request)
    {
        $q = $request->query->get('q');
        echo $q; exit;
    }
}
