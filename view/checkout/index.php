<div class="row">
  <div class="col-md-12">
  <div class="row p-2 mb-2 mr-1 ml-1 mt-2 rounded-sm bg-gray-100 shadow-sm border">
        <div class="col-md-12">
          <h5 class="text-danger">Order Summary</h5>
          <table class="table table-condensed mt-1">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th width=148>Quantity</th>
                    <th width=100>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img class="card-img-top" src="<?php $this->getSiteUrl(); ?>images/slide2.jpg" alt="Card image cap" style="height:40px; width:40px;"></td>
                    <td style="max-width:170px;">Beef Premium</td>
                    <td style="max-width:170px;">Testing description...</td>
                    <td style="max-width:170px;">1</td>
                    <td style="max-width:170px;">₱380</td>
                </tr>
                <tr>
                    <td><img class="card-img-top" src="<?php $this->getSiteUrl(); ?>images/slide1.jpg" alt="Card image cap" style="height:40px; width:40px;"></td>
                    <td style="max-width:170px;">Samgyupsal Meat Premium</td>
                    <td style="max-width:170px;">Testing description...</td>
                    <td style="max-width:170px;">2</td>
                    <td style="max-width:170px;">₱750</td>
                </tr>
            </tbody>
        </table><hr/>
          <span><strong>Total Items: </strong> 2</span><br/>
          <span><strong>Subtotal: </strong> ₱ 1130</span>
        </div>
    </div>
    <div class="row m-1 pt-2">
        <div class="col-md-5">
            <div class="btn-group mb-3" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-danger">COD</button>
                <button type="button" class="btn btn-danger">GCash</button>
                <button type="button" class="btn btn-outline-danger">Credit/Debit Card</button>
            </div>
            <p><i class="fas fa-money-check"></i> Payment Method: GCash</p>
            <div class="row-block p-3 mb-2 mr-1 mt-n2 rounded-sm shadow-sm border">
                <span class="text-primary font-weight-bold">GCash Account</span><br/>
                <span>Account Name: Juan Dela Cruz</span><br/>
                <span>Mobile No: 09053228055</span>
            </div>
        </div>
        <div class="col-md-7">
            <button class="btn btn-sm btn-outline-danger pull-right my-2 my-sm-0 mr-1">Place Order Now</button>
            <a href="cart" class="btn btn-sm btn-danger pull-right my-2 my-sm-0 mr-1">Back to Cart</a>
        </div>
    </div>
  </div>
</div>
<script>
    $(function() {
        $('.qty-input').prop('disabled', true);
        
        $('.plus-btn').on('click', function() {
            var qty = $(this).parents('.qty-group').find('.qty-input');
            qty.val(parseInt(qty.val()) + 1);
        });

        $('.minus-btn').on('click', function() {
            var qty = $(this).parents('.qty-group').find('.qty-input');
            qty.val(parseInt(qty.val()) - 1);
            if (qty.val() == 0) {
                qty.val(1);
            }
        });
    });
</script>