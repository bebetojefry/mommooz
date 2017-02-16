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
        $em = $this->getDoctrine()->getManager();     
        
        $qb = $em->createQueryBuilder();
        $qb->select('e')
            ->from('AppFrontBundle:StockEntry', 'e')
            ->leftJoin('e.item', 'i')
            ->leftJoin('i.keywords','k1')
            ->leftJoin('i.product','p')
            ->leftJoin('p.category','c')
            ->leftJoin('c.keywords','k4')
            ->leftJoin('p.keywords','k2')
            ->leftJoin('i.brand','b')
            ->leftJoin('b.keywords','k3')
            ->where($qb->expr()->orX(
                $qb->expr()->like('k1.keyword', '?1'),
                $qb->expr()->like('k2.keyword', '?1'),
                $qb->expr()->like('k3.keyword', '?1'),
                $qb->expr()->like('k4.keyword', '?1'),
                $qb->expr()->like('c.categoryName', '?1'),
                $qb->expr()->like('p.name', '?1'),
                $qb->expr()->like('i.name', '?1'),
                $qb->expr()->like('b.name', $qb->expr()->literal('Fru%'))
            ))
            ->orderBy('e.id', 'ASC')
            ->setParameter(1, $q);
        
        $entries = $qb->getQuery()->getResult();
        
        return $this->render('AppWebBundle:Search:items.html.twig', array(
            'entries' => $entries
        ));
    }
}
