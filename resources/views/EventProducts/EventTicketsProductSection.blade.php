<tr>
    <td colspan="3" class="order-form-product-entry">
        <h3>MERCHANDISE</h3>
        @php
        $products = App\Http\Controllers\WooCommerceController::getShopProducts($product_ids);   
        @endphp

        @foreach ($products as $product)
            <div class="order-form-product-entry">
                <div class="row">
                    @php
                    if($product['type'] == 'simple'):
                    @endphp
SIMPLE

                    @php
                    endif;
                    @endphp


                    <div class="col-md-5">
                        @php
                            // if(isset($product->images[0])) {
                            //     echo '<img src="' . $product->images[0]->src . '" alt="" />';
                            // }
                        @endphp
                    </div>
                    <div class="col-md-7">
                        <h4></h4>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Quantity</label>
                                    <select class="form-control">
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
                </div>

            </div>
        @endforeach
    </td>
</tr>