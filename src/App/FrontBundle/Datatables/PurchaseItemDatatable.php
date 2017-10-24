<?php

namespace App\FrontBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class PurchaseItemDatatable
 *
 * @package App\FrontBundle\Datatables
 */
class PurchaseItemDatatable extends AbstractDatatableView
{
    public $requestStack;
    private $start = 0;
    private $sl = 1;
    
    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $this->start = $this->getRequest()->query->get('start', 0);
        $formatter = function($line){
            $line['sl'] = $this->start + $this->sl++;

            return $line;
        };

        return $formatter;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->topActions->set(array(
            'start_html' => '<div class="row"><div class="col-sm-12">',
            'end_html' => '<hr></div></div>',
            'actions' => array()
        ));

        $this->features->set(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => true,
            'jquery_ui' => false,
            'length_change' => true,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'state_save' => true,
            'delay' => 0,
            'extensions' => array(),
            'highlight' => false,
            'highlight_color' => 'red'
        ));

        if(isset($options['purchase'])){
            $route = $this->router->generate('purchaseitem_results', array('id' => $options['purchase']->getId()));
        } else if(isset($options['vendor'])) {
            $route = $this->router->generate('app_front_vendor_orders_result', array('id' => $options['vendor']->getId()));
        } else {
            $route = $this->router->generate('purchaseitem_results');
        }
        
        $this->ajax->set(array(
            'url' => $route,
            'type' => 'GET',
            'pipeline' => 0
        ));

        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(0, 'asc')),
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '',
            'scroll_collapse' => false,
            'search_delay' => 0,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'class' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'force_dom' => false,
            'row_id' => 'id'
        ));

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => 'Id',
                'visible' => false,
            ))
            ->add('sl', 'virtual', array(
                'title' => 'Sl No',
            ));
        
        if(!isset($options['vendor'])){
            $this->columnBuilder->add('entry.stock.vendor.firstname', 'column', array(
                'title' => 'Vendor',
            ));
        }
        
        if(isset($options['vendor'])){
            $this->columnBuilder->add('purchase.consumer.firstname', 'column', array(
                'title' => 'Consumer',
            ));
        }
            $this->columnBuilder->add('entry.item.name', 'column', array(
                'title' => 'Item',
            ))
            ->add('entry.item.brand.name', 'column', array(
                'title' => 'Brand',
            ))
            ->add('entry.variant.value', 'column', array(
                'title' => 'Variant',
            ))
            ->add('quantity', 'column', array(
                'title' => 'Quantity',
            ))
            ->add('unit_price', 'column', array(
                'title' => 'Unit Price',
            ))
            ->add('price', 'column', array(
                'title' => 'Total Price',
            ));
                    
        if(isset($options['vendor'])){
            $this->columnBuilder->add('purchase.purchasedOn', 'datetime', array(
                'title' => 'Purchased On',
            ));
        }
        
        
        $this->callbacks->set(array(
            'row_callback' => array(
                'template' => 'AppFrontBundle:DataTable:row_callback.js.twig',
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'App\FrontBundle\Entity\PurchaseItem';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'purchaseitem_datatable';
    }
}
