<?php

/**
 * @since      1.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce_Public
{
    public function woocommerce_page_view()
    {
        $domain = get_option( 'dipi_woocommerce_cname', 'dipi.io' );
        $brand_id = get_option( 'dipi_woocommerce_brand_id' );

        if ( $brand_id ) {
            echo "
                <div id='dp_$brand_id'></div>
                <script>(function() { if (document.cookie.indexOf('dp_$brand_id') === -1) { document.getElementById('dp_$brand_id').setAttribute('data-dp', Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)); var dpd = new Date(); dpd = new Date(dpd.getTime()+1000*60*60*24*365); document.cookie = 'dp_$brand_id='+document.getElementById('dp_$brand_id').getAttribute('data-dp')+'; expires='+dpd.toGMTString()+'; path=/'; } })();</script>
                <script>(function() { var dipi_params = new URLSearchParams(window.location.search); if (dipi_params.has('dipi')) { var _dp1 = document.createElement('iframe'); _dp1.frameBorder = 0; _dp1.style.height = 0; _dp1.style.width = 0; _dp1.src = '//'.$domain.'/t/c/e/'+dipi_params.get('dipi')+'/?c='+btoa(document.cookie); var _dp2 = document.getElementsByTagName('script')[0]; _dp2.parentNode.insertBefore(_dp1, _dp2); } })();</script>
            ";
        }
    }

    public function woocommerce_thankyou( $order_id )
    {
        $domain = get_option( 'dipi_woocommerce_cname', 'dipi.io' );
        $brand_id = get_option( 'dipi_woocommerce_brand_id' );

        if ( $order_id && $brand_id ) {
            $order = wc_get_order( $order_id );
            $coupons = null;
            if ( count( $order->get_used_coupons() ) > 0 ) {
                $coupons = implode( ',', $order->get_used_coupons() );
            }
            // Get all customer orders
            $customer_orders = get_posts( array(
                'numberposts' => 1,
                'meta_key'    => '_customer_user',
                'meta_value'  => get_current_user_id(),
                'post_type'   => 'shop_order',
                'post_status' => 'wc-completed',
                'fields'      => 'ids',
            ) );

            echo "
                <script>
                var _dipi = _dipi || [];
                _dipi['order_reference'] = '".$order_id."';
                _dipi['order_amount'] = '".$order->get_total()."';
                _dipi['order_currency'] = '".get_woocommerce_currency()."';
                _dipi['voucher_code'] = '".$coupons."';
                _dipi['new_customer'] = '".(count($customer_orders) > 0 ? 1 : 0)."';
                (function() { var _dpp1 = document.createElement('iframe'); _dpp1.frameBorder = 0; _dpp1.style.height = 0; _dpp1.style.width = 0; _dpp1.src = '//'.$domain.'/t/px/$brand_id/?or='+_dipi['order_reference']+'&oa='+_dipi['order_amount']+'&oc='+_dipi['order_currency']+'&vc='+_dipi['voucher_code']+'&nc='+_dipi['new_customer']+'&c='+btoa(document.cookie); var _dpp2 = document.getElementsByTagName('script')[0]; _dpp2.parentNode.insertBefore(_dpp1, _dpp2);
                })();
                </script>
            ";
        }        
    }
}