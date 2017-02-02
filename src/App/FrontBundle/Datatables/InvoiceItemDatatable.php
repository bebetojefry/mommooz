<?php

namespace App\FrontBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class PurchaseItemDatatable
 *
 * @package App\FrontBundle\Datatables
 */
class InvoiceItemDatatable extends AbstractDatatableView
{
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
            'state_save' => false,
            'delay' => 0,
            'extensions' => array(),
            'highlight' => false,
            'highlight_color' => 'red'
        ));

        $route = $this->router->generate('invoice_item_results', array('id' => $options['invoice']->getId()));
        
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
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'force_dom' => false,
            'row_id' => 'id'
        ));

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => 'Id',
            ))
            ->add('entry.item.name', 'column', array(
                'title' => 'Item',
            ))
            ->add('entry.item.brand.name', 'column', array(
                'title' => 'Brand',
            ))
            ->add('entry.variant.value', 'column', array(
                'title' => 'Variant',
            ))
            ->add('purchase.purchasedOn', 'datetime', array(
                'title' => 'Purchased On',
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
        return 'invoiceitem_datatable';
    }
}
