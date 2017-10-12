<?php

namespace App\FrontBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
use App\FrontBundle\Entity\Consumer;

/**
 * Class PurchaseDatatable
 *
 * @package App\FrontBundle\Datatables
 */
class PurchaseDatatable extends AbstractDatatableView
{
    private $sl = 1;
    
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function($line){
            $states = array(0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3=> "Out for delivered", 4 => 'Delivered', 5 => 'Cancelled', 6 => 'Cleared');
            $line['state'] = $states[$line['status']];
            $line['sl'] = $this->sl++;
            
            return $line;
        };

        return $formatter;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $id = (isset($options['consumer']) && $options['consumer'] instanceof Consumer) ? $options['consumer']->getId() : 0;
        $this->topActions->set(array(
            'start_html' => '<div class="row"><div class="col-sm-12">',
            'actions' => array(),
            'end_html' => '<hr></div></div>'
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

        $this->ajax->set(array(
            'url' => $this->router->generate('purchase_results', array('id' => $id)),
            'type' => 'GET',
            'pipeline' => 0
        ));

        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(0, 'desc')),
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
            ->add('consumer.firstname', 'column', array(
                'title' => 'Ordered By',
            ))    
            ->add('purchasedOn', 'datetime', array(
                'title' => 'Purchased On',
            ))
            ->add('status', 'column', array('visible' => false))
            ->add('state', 'virtual', array(
                'title' => 'Status',
            ))
            ->add('method', 'column', array(
                'title' => 'Method',
            ))    
            ->add('deliveredOn', 'datetime', array(
                'title' => 'Delivered On',
            ))
            ->add('deliveredBy', 'column', array(
                'title' => 'Delivered By',
            ))
            ->add(null, 'action', array(
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'purchase_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button',
                            'style' => 'margin-right:5px;'
                        ),
                    ),
                    array(
                        'route' => 'change_status',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('purchase.actions.status'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('purchase.actions.status'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button',
                            'onclick' => 'return openModal(event);',
                            'modalTitle' => $this->translator->trans('purchase.title.status'),
                            'style' => 'margin-right:5px;'
                        ),
                    ),
                    array(
                        'route' => 'purchase_clear',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('purchase.actions.clear'),
                        'icon' => 'glyphicon glyphicon-th-list',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('purchase.actions.clear'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button',
                            'onclick' => 'return openConfirm(event);',
                            'cofirmText' => $this->translator->trans('purchase.clear.confirm'),
                        ),
                        'render_if' => function($row) {                            ;
                            return $row['status'] == 5;
                        }
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
        return 'App\FrontBundle\Entity\Purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'purchase_datatable';
    }
}
