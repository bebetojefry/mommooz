<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\FrontBundle\Entity\Keyword;

class KeywordController extends Controller
{
    /**
     * @Route("/search", name="keyword_search")
     */
    public function searchAction(Request $request){
        $q = $request->query->get('q');
        $keywords = $this->getDoctrine()->getManager()->getRepository("AppFrontBundle:Keyword")->createQueryBuilder('k')
        ->where('k.keyword LIKE :q')
        ->setParameter('q', '%'.$q.'%')
        ->getQuery()
        ->getResult();
        
        $result = array();
        foreach($keywords as $keyword){
            $result[] = array('id' => $keyword->getId(), 'name' => $keyword->getKeyword());
        }
        
        return new JsonResponse($result);
    }
}
