<div class="wrap dipi-woocommerce">
    <h2>Dipi WooCommerce Settings</h2>
    <form method="post" action="?action=dipi-woocommerce-save-settings">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="dipi_woocommerce_brand_id">Dipi Brand ID</label>
                    </th>
                    <td>
                        <input name="dipi_woocommerce_brand_id" id="dipi_woocommerce_brand_id" type="number" value="<?php echo get_option( 'dipi_woocommerce_brand_id' ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="dipi_woocommerce_cname">CNAME</label>
                    </th>
                    <td>
                        <input name="dipi_woocommerce_cname" id="dipi_woocommerce_cname" type="text" value="<?php echo get_option( 'dipi_woocommerce_cname' ); ?>">
                        <p>Leave empty if this you do not have any custom domain connected to dipi.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="dipi_woocommerce_coupon_token">Coupon API token</label>
                    </th>
                    <td>
                        <input name="dipi_woocommerce_coupon_token" id="dipi_woocommerce_coupon_token" type="text" value="<?php echo get_option( 'dipi_woocommerce_coupon_token' ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="dipi_woocommerce_coupon_expiry_days">Coupon Expiry Days</label>
                    </th>
                    <td>
                        <input name="dipi_woocommerce_coupon_expiry_days" id="dipi_woocommerce_coupon_expiry_days" type="number" value="<?php echo get_option( 'dipi_woocommerce_coupon_expiry_days', 60 ); ?>">
                        Days
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="dipi_woocommerce_coupon_description">Coupon Description</label>
                    </th>
                    <td>
                        <input name="dipi_woocommerce_coupon_description" id="dipi_woocommerce_coupon_description" type="text" value="<?php echo get_option( 'dipi_woocommerce_coupon_description' ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label>Coupon API endpoint</label>
                    </th>
                    <td>
                        <?php echo home_url() . '/wp-json/dipi/v1/coupons?k=' . $coupon_url_key; ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"></th>
                    <td>
                        <button type="submit" class="button-primary">Save</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>