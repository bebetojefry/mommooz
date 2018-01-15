<?php

namespace App\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\FrontBundle\Handler\CmsAnnotationHandler;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends FOSRestController
{
    /**
     * @Rest\Get("/home_api")
     */
    public function homeAction(Request $request)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $imagine_service = $this->get('liip_imagine.controller');
        $app_web_user = $this->get('app.web.user');

        $data['banners'] = array();
        $banners = $em->getRepository('AppFrontBundle:Banner')->findAll();

        foreach($banners as $banner){
            $reflect = new \ReflectionClass(get_class($banner));

            $imagemanagerResponse = $imagine_service
                ->filterAction(
                    $request,
                    $banner->getRealPicturePath(),
                    'banner'
                );

            $data['banners'][] = array(
                'banner_image' => $imagemanagerResponse->getTargetUrl(),
                'banner_id' => $banner->getId(),
                'banner_link' => $this->generateUrl($banner->getRouteName(), array('id' => $banner->getId()), true),
                'banner_type' => $reflect->getShortName()
            );
        }

        $data['category_list'] = array();
        $categories = $em->getRepository('AppFrontBundle:Category')->findBy(array('status' => true));
        foreach($categories as $category){
            $imagemanagerResponse = $imagine_service
                ->filterAction(
                    $request,
                    $category->getRealPicturePath(),
                    'menu_cat'
                );

            $data['category_list'][] = array(
                'cat_id' => $category->getId(),
                'cat_image' => $imagemanagerResponse->getTargetUrl(),
                'cat_name' => $category->getCategoryName()
            );
        }

        $data['best_deals'] = array();
        $cat = $em->getRepository('AppFrontBundle:Category')->find($this->getParameter('best_deals_cat'));
        if($cat){
            $qb = $em->createQueryBuilder();
            $qb->select('se')
                ->from('AppFrontBundle:StockEntry', 'se')
                ->join('se.item', 'i')
                ->join('i.categories', 'c')
                ->where('c.id = :cat_id')
                ->andWhere('se.status = :status')
                ->setParameter('status', true)
                ->setParameter('cat_id', $cat->getId());


            $entries = $qb->getQuery()->getResult();

            $i = 0;
            foreach($entries as $entry){
                //if($app_web_user->isDeliverable($entry) && $entry->isEnabled()) {
                if($entry->isEnabled()) {
                    $imagemanagerResponse = $imagine_service
                        ->filterAction(
                            $request,
                            $entry->getItem()->getRealPicturePath(),
                            'item_thumb'
                        );

                    $data['best_deals'][$i] = array(
                        'product_id' => $entry->getId(),
                        'product_name' => $entry->getItem()->getName(),
                        'product_image' => $imagemanagerResponse->getTargetUrl(),
                        'cutprice' => $entry->getMrp(),
                        'price' => $entry->getActualPrice(),
                        'wishlist_status' => false,
                    );

                    $data['best_deals'][$i]['variants'] = array();
                    foreach($entry->getItem()->getVariantsInStock() as $variantType){
                        if(count($variantType) > 0) {
                            foreach($variantType as $variant){
                                $variantEntry = $entry->getItem()->getEntryForVariant($variant);
                                if($variantEntry && $variantEntry->getId() != $entry->getId()) {
                                    if ($variantEntry->isEnabled()) {
                                        $data['best_deals'][$i]['variants'][] = array(
                                            'variant_id' => $variantEntry->getId(),
                                            'variant_unit' => $variant->getValue()
                                        );
                                    }
                                }
                            }
                        }
                    }

                    $i++;
                }
            }
        }

        $data['traditional'] = array();
        $cat = $em->getRepository('AppFrontBundle:Category')->find($this->getParameter('traditional_cat'));
        if($cat){
            $qb = $em->createQueryBuilder();
            $qb->select('se')
                ->from('AppFrontBundle:StockEntry', 'se')
                ->join('se.item', 'i')
                ->join('i.categories', 'c')
                ->where('c.id = :cat_id')
                ->andWhere('se.status = :status')
                ->setParameter('status', true)
                ->setParameter('cat_id', $cat->getId());


            $entries = $qb->getQuery()->getResult();

            $i = 0;
            foreach($entries as $entry){
                //if($app_web_user->isDeliverable($entry) && $entry->isEnabled()) {
                if($entry->isEnabled()) {
                    $imagemanagerResponse = $imagine_service
                        ->filterAction(
                            $request,
                            $entry->getItem()->getRealPicturePath(),
                            'item_thumb'
                        );

                    $data['traditional'][$i] = array(
                        'product_id' => $entry->getId(),
                        'product_name' => $entry->getItem()->getName(),
                        'product_image' => $imagemanagerResponse->getTargetUrl(),
                        'cutprice' => $entry->getMrp(),
                        'price' => $entry->getActualPrice(),
                        'wishlist_status' => false,
                    );

                    $data['traditional'][$i]['variants'] = array();
                    foreach($entry->getItem()->getVariantsInStock() as $variantType){
                        if(count($variantType) > 0) {
                            foreach($variantType as $variant){
                                $variantEntry = $entry->getItem()->getEntryForVariant($variant);
                                if($variantEntry && $variantEntry->getId() != $entry->getId()) {
                                    if ($variantEntry->isEnabled()) {
                                        $data['traditional'][$i]['variants'][] = array(
                                            'variant_id' => $variantEntry->getId(),
                                            'variant_unit' => $variant->getValue()
                                        );
                                    }
                                }
                            }
                        }
                    }

                    $i++;
                }
            }
        }

        $data['best_offer'] = array();
        $now = new \DateTime('now');
        $now->setTime(0, 0, 0);
        $offers = $em->getRepository("AppFrontBundle:Offer")->createQueryBuilder('o')
            ->where('o.status = :s and o.expiry >= :now')
            ->setParameter('s', true)
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();

        $i = 0;
        foreach($offers as $offer){
            $offerItems = $app_web_user->getOfferItems($offer);

            foreach($offerItems as $entry) {
                //if($app_web_user->isDeliverable($entry) && $entry->isEnabled()) {
                if ($entry->isEnabled()) {
                    $imagemanagerResponse = $imagine_service
                        ->filterAction(
                            $request,
                            $entry->getItem()->getRealPicturePath(),
                            'item_thumb'
                        );

                    $data['best_offer'][$i] = array(
                        'product_id' => $entry->getId(),
                        'product_name' => $entry->getItem()->getName(),
                        'product_image' => $imagemanagerResponse->getTargetUrl(),
                        'cutprice' => $entry->getMrp(),
                        'price' => $entry->getActualPrice(),
                        'wishlist_status' => false,
                    );

                    $data['best_offer'][$i]['variants'] = array();
                    foreach ($entry->getItem()->getVariantsInStock() as $variantType) {
                        if (count($variantType) > 0) {
                            foreach ($variantType as $variant) {
                                $variantEntry = $entry->getItem()->getEntryForVariant($variant);
                                if ($variantEntry && $variantEntry->getId() != $entry->getId()) {
                                    if ($variantEntry->isEnabled()) {
                                        $data['best_offer'][$i]['variants'][] = array(
                                            'variant_id' => $variantEntry->getId(),
                                            'variant_unit' => $variant->getValue()
                                        );
                                    }
                                }
                            }
                        }
                    }

                    $i++;
                }
            }
        }

        $data['Wish_list _count']  = 0;
        $data['cart _count'] = 0;

        $view = $this->view($data);
        return $this->handleView($view);
    }
}
