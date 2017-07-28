<?php

namespace App\FrontBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
use App\FrontBundle\Entity\Cart;

/**
 * Class CartItemDatatable
 *
 * @package App\FrontBundle\Datatables
 */
class CartItemDatatable extends AbstractDatatableView
{
    private $sl = 1;
    
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function($line){
            $line['sl'] = $this->sl++;
            $line['net_price'] = $line['quantity'] * $line['price'];
            return $line;
        };

        return $formatter;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $id = (isset($options['cart']) && $options['cart'] instanceof Cart) ? $options['cart']->getId() : 0;
        $this->topActions->set(array(
            'start_html' => '<div class="row"><div class="col-sm-12">',
            'end_html' => '<hr></div></div>',
            'actions' => array(
                array(
                    'route' => $this->router->generate('add_item_to_cart', array('id' => $id)),
                    'label' => $this->translator->trans('cart.actions.add_item'),
                    'icon' => 'glyphicon glyphicon-plus',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => $this->translator->trans('cart.title.new_item'),
                        'class' => 'btn btn-primary',
                        'role' => 'button'
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
            'url' => $this->router->generate('cart_results', array('id' => $id)),
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
            ->add('entry.item.brand.name', 'column', array(
                'title' => 'Brand',
            ))
            ->add('entry.item.name', 'column', array(
                'title' => 'Item',
            ))
            ->add('quantity', 'column', array(
                'title' => 'Quantity',
            ))
            ->add('price', 'column', array(
                'title' => 'Unit Price',
            ))
            ->add('net_price', 'virtual', array(
                'title' => 'Net Price',
            ))
            ->add('addedOn', 'datetime', array(
                'title' => 'Added On',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'App\FrontBundle\Entity\CartItem';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cartitem_datatable';
    }
}
