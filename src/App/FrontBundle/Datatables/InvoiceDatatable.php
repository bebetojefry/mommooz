<?php

namespace App\FrontBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
use App\FrontBundle\Entity\Vendor;

/**
 * Class InvoiceDatatable
 *
 * @package App\FrontBundle\Datatables
 */
class InvoiceDatatable extends AbstractDatatableView
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
            'actions' => array(
                array(
                    'route' => $this->router->generate('invoice_new'),
                    'label' => $this->translator->trans('datatables.actions.new'),
                    'icon' => 'glyphicon glyphicon-plus',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => $this->translator->trans('datatables.actions.new'),
                        'class' => 'btn btn-primary',
                        'role' => 'button',
                        'onclick' => 'return openModal(event);',
                        'modalTitle' => $this->translator->trans('invoice.title.new'),
                    ),
                )
            )
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

        $id = isset($options['vendor']) && $options['vendor'] instanceof Vendor ? $options['vendor']->getId() : 0;
        
        $details_route = isset($options['vendor']) && $options['vendor'] instanceof Vendor ? 'app_front_vendor_invoice_items' : 'invoice_show';
        
        $this->ajax->set(array(
            'url' => $this->router->generate('invoice_results', array('id' => $id)),
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
                'visible' => false,
            ))
            ->add('sl', 'virtual', array(
                'title' => 'Sl No',
            ))
            ->add('vendor.firstname', 'column', array(
                'title' => 'Store',
            ))
            ->add('dateFrom', 'datetime', array(
                'title' => 'DateFrom',
            ))
            ->add('dateTo', 'datetime', array(
                'title' => 'DateTo',
            ))
            ->add('createdOn', 'datetime', array(
                'title' => 'CreatedOn',
            ))
            ->add('status', 'boolean', array(
                'title' => 'Status',
                'true_label' => 'Proccessed',
                'false_label' => 'Generated'
            ))
            ->add(null, 'action', array(
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => $details_route,
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ))
        ;
        
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
        return 'App\FrontBundle\Entity\Invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'invoice_datatable';
    }
}
