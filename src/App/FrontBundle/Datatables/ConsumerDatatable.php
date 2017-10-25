<?php

namespace App\FrontBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class ConsumerDatatable
 *
 * @package App\FrontBundle\Datatables
 */
class ConsumerDatatable extends AbstractDatatableView
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
                    'route' => $this->router->generate('consumer_new'),
                    'label' => $this->translator->trans('consumer.actions.new'),
                    'icon' => 'glyphicon glyphicon-plus',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => $this->translator->trans('consumer.actions.new'),
                        'class' => 'btn btn-primary',
                        'role' => 'button',
                        'onclick' => 'return openModal(event);',
                        'modalTitle' => $this->translator->trans('consumer.title.new'),
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

        $this->ajax->set(array(
            'url' => $this->router->generate('consumer_results'),
            'type' => 'GET',
            'pipeline' => 0
        ));

        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(5, 'desc')),
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
            ))
            ->add('firstname', 'column', array(
                'title' => 'Firstname',
            ))
            ->add('lastname', 'column', array(
                'title' => 'Lastname',
            ))
            ->add('phone', 'column', array(
                'title' => 'Phone',
            ))
            ->add('created_on', 'datetime', array(
                'title' => 'Created On',
            ))
            ->add(null, 'action', array(
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'consumer_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('datatables.actions.edit'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button',
                            'onclick' => 'return openModal(event);',
                            'modalTitle' => $this->translator->trans('consumer.title.edit'),
                            'style' => 'margin-right:5px;'
                        )
                    ),
                    array(
                        'route' => 'consumer_delete',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('datatables.actions.delete'),
                        'icon' => 'glyphicon glyphicon-trash',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.delete'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button',
                            'onclick' => 'return openConfirm(event);',
                            'cofirmText' => $this->translator->trans('consumer.delete.confirm'),
                            'style' => 'margin-right:5px;'
                        ),
                    ),
                    array(
                        'route' => 'consumer_cart',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('consumer.actions.cart'),
                        'icon' => 'glyphicon glyphicon-shopping-cart',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'class' => 'btn btn-primary btn-xs',
                            'style' => 'margin-right:5px;'
                        ),
                    ),
                    array(
                        'route' => 'consumer_orders',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('consumer.actions.orders'),
                        'icon' => 'glyphicon glyphicon-shopping-cart',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'class' => 'btn btn-primary btn-xs',
                            'style' => 'margin-right:5px;'
                        ),
                    ),array(
                        'route' => 'consumer_reset',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('vendor.actions.reset'),
                        'icon' => 'glyphicon',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('vendor.actions.reset'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button',
                            'onclick' => 'return openModal(event);',
                            'modalTitle' => $this->translator->trans('vendor.title.reset'),
                            'style' => 'margin-right:5px;'
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
        return 'App\FrontBundle\Entity\Consumer';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'consumer_datatable';
    }
}
