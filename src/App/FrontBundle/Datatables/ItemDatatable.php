<?php

namespace App\FrontBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
use App\FrontBundle\Entity\Product;
use App\FrontBundle\Entity\Brand;

/**
 * Class ItemDatatable
 *
 * @package App\FrontBundle\Datatables
 */
class ItemDatatable extends AbstractDatatableView
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
        $id = (isset($options['product']) && $options['product'] instanceof Product) ? $options['product']->getId() : 0;
        $this->topActions->set(array(
            'start_html' => '<div class="row"><div class="col-sm-12">',
            'end_html' => '<hr></div></div>',
            'actions' => array(
                array(
                    'route' => $this->router->generate('item_new', array('id' => $id)),
                    'label' => $this->translator->trans('datatables.actions.new'),
                    'icon' => 'glyphicon glyphicon-plus',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => $this->translator->trans('datatables.actions.new'),
                        'class' => 'btn btn-primary',
                        'role' => 'button',
                        'onclick' => 'return openModal(event);',
                        'modalTitle' => $this->translator->trans('item.title.new'),
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

        $brandId = (isset($options['brand']) && $options['brand'] instanceof Brand) ? $options['brand']->getId() : 0;
        $this->ajax->set(array(
            'url' => $this->router->generate('item_results', array('id' => $id, 'brand' => $brandId)),
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
            ))
            ->add('name', 'column', array(
                'title' => 'Name',
                'searchable' => true,
            ))
            ->add('product.name', 'column', array(
                'title' => 'Product',
                'searchable' => true,
            ))
            ->add('brand.name', 'column', array(
                'title' => 'Brand',
                'searchable' => true,
            ))
            ->add('status', 'boolean', array(
                'title' => 'Status',
                'true_icon' => 'glyphicon glyphicon-ok',
                'false_icon' => 'glyphicon glyphicon-remove',
                'true_label' => 'Yes',
                'false_label' => 'No'
            ))
            ->add(null, 'action', array(
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'item_edit',
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
                            'modalTitle' => $this->translator->trans('item.title.new'),
                            'style' => 'margin-right:5px;'
                        ),
                    ),
                    array(
                        'route' => 'item_delete',
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
                            'cofirmText' => $this->translator->trans('item.delete.confirm')
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
        return 'App\FrontBundle\Entity\Item';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'item_datatable';
    }
}
