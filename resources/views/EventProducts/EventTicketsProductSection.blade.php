<tr>
    <td colspan="3" class="order-form-product-entry">
        <h3>MERCHANDISE</h3>
        @php
        $products = App\Http\Controllers\WooCommerceController::getShopProducts($product_ids);   
        @endphp

        @foreach ($products as $product)
            <div class="order-form-product-entry">
                <div class="row">
                    <?php
                    // echo "<pre>";
                    // print_r($product);
                    // echo "</pre>";
                    ?>

                    @php
                    if($product['type'] == 'bundle'):
                    @endphp

                        <div class="col-md-5">
                            @php
                                if(isset($product['image'])) {
                                    echo '<img src="' . $product['image'] . '" alt="" />';
                                }
                            @endphp
                        </div>
                        <div class="col-md-7">
                            <h4>
                                @php
                                echo $product['name'];
                                @endphp
                            </h4>
                        </div>
                        <div class="col-md-5">
                            @foreach ($product['products'] as $prod)
                                <h5>
                                    @php
                                    echo $prod['name'];
                                    @endphp
                                </h5>

                                @php
                                if($prod['type'] == 'simple'):
                                @endphp

                                    <p class="<?php echo $prod['stock_level']; ?>">
                                        @php
                                        echo $prod['stock_amount'];   
                                        @endphp
                                    </p>

                                @php
                                endif;
                                @endphp

                                @php
                                if($prod['type'] == 'variable'):
                                @endphp

                                    @foreach ($prod['variations'] as $p)
                                        <p>
                                            @php
                                            echo $p['name'];   
                                            @endphp
                                        </p>

                                        <p class="<?php echo $p['stock_level']; ?>">
                                            @php
                                            echo $p['stock_amount'];   
                                            @endphp
                                        </p>
                                    @endforeach

                                @php
                                endif;
                                @endphp

                                
                            @endforeach

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Quantity</label>
                                        @php
                                        $html_name = $product['id'] . '_';
                                        @endphp
                                        <select name="<?php echo $html_name; ?>" class="form-control">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex h-100">
                                            <button class="btn btn-primary order-product-basket-btn">Add to basket</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @php
                    endif;
                    @endphp









                    @php
                    if($product['type'] == 'variable'):
                    @endphp

                        <div class="col-md-5">
                            @php
                                if(isset($product['image'])) {
                                    echo '<img src="' . $product['image'] . '" alt="" />';
                                }
                            @endphp
                        </div>
                        <div class="col-md-7">
                            <h4>
                                @php
                                echo $product['name'];
                                @endphp
                            </h4>

                            <p>
                                @php
                                echo $product['price_range'];    
                                @endphp
                            </p>

                            @foreach ($product['variations'] as $var)
                            <div class="row">
                                    <div class="col-md-6">
                                            <h5>
                                                @php
                                                echo $var['name'];
                                                @endphp
                                            </h5>

                                            <p class="<?php echo $var['stock_level']; ?>">
                                                @php
                                                    echo $var['stock_amount'];   
                                                @endphp
                                            </p>
                    
                                            <p>
                                                @php
                                                echo $var['price'];   
                                                @endphp
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">Quantity</label>
                                                        @php
                                                        $html_value = $product['id'] . '_' . $var['id'] . '_0_';
                                                        @endphp
                                                        <select name="ap_ticketing_products[]" class="form-control">
                                                            <option value="<?php echo $html_value . '0'; ?>">0</option>
                                                            <option value="<?php echo $html_value . '1'; ?>">1</option>
                                                            <option value="<?php echo $html_value . '2'; ?>">2</option>
                                                            <option value="<?php echo $html_value . '3'; ?>">3</option>
                                                            <option value="<?php echo $html_value . '4'; ?>">4</option>
                                                            <option value="<?php echo $html_value . '5'; ?>">5</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </div>

                            @endforeach

                        </div>

                    @php
                    endif;
                    @endphp

                    @php
                    if($product['type'] == 'simple'):
                    @endphp
                    
                        <div class="col-md-5">
                            @php
                                if(isset($product['image'])) {
                                    echo '<img src="' . $product['image'] . '" alt="" />';
                                }
                            @endphp
                        </div>
                        <div class="col-md-7">
                            <h4>
                                @php
                                echo $product['name'];
                                @endphp
                            </h4>
                            <p class="<?php echo $product['stock_level']; ?>">
                                @php
                                 echo $product['stock_amount'];   
                                @endphp
                            </p>
    
                            <p>
                                @php
                                echo $product['price'];   
                                @endphp
                            </p>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Quantity</label>
                                        @php
                                        $html_value = $product['id'] . '_0_0_';
                                        @endphp
                                        <select name="ap_ticketing_products[]" class="form-control">
                                            <option value="<?php echo $html_value . '0'; ?>">0</option>
                                            <option value="<?php echo $html_value . '1'; ?>">1</option>
                                            <option value="<?php echo $html_value . '2'; ?>">2</option>
                                            <option value="<?php echo $html_value . '3'; ?>">3</option>
                                            <option value="<?php echo $html_value . '4'; ?>">4</option>
                                            <option value="<?php echo $html_value . '5'; ?>">5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @php
                    endif;
                    @endphp

                </div>

            </div>
        @endforeach
    </td>
</tr>