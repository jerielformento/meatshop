<style>
    /* The side navigation menu */
.sidebar {
  margin: 20px 0 0 0;
  padding: 0;
  width: 200px;
  height: auto;
  overflow: auto;
}

/* Sidebar links */
.sidebar a {
  display: block;
  color: #555;
  padding: 8px;
  text-decoration: none;
  border-left:3px solid #fff;
}

/* Active/current link */
.sidebar a.active {
  border-left:3px solid #DC3545;
  color: #DC3545;
}

/* Links on mouse-over */
.sidebar a:hover:not(.active) {
  border-left:3px solid #ddd;
}

/* Page content. The value of the margin-left property should match the value of the sidebar's width property */
div.content {
  margin-left: 200px;
  padding: 1px 16px;
  height: auto;
}

/* On screens that are less than 700px wide, make the sidebar into a topbar */
@media screen and (max-width: 700px) {
  .sidebar {
    width: 100%;
    height: auto;
  }
  .sidebar a {float: left;}
  div.content {margin-left: 0;}
}

/* On screens that are less than 400px, display the bar vertically, instead of horizontally */
@media screen and (max-width: 400px) {
  .sidebar a {
    text-align: center;
    float: none;
  }
}
</style>

<div class="row">
  <div class="col-md-3" style="height:175px;">
    <div class="sidebar">
    <a href="/cart"><i class="fa fa-cart-arrow-down"></i> Shopping Cart</a>
    <a href="/cart/pack"><i class="fas fa-shopping-bag"></i> To Pack</a>
    <a class="active" href="#"><i class="fas fa-shipping-fast"></i> To Ship</a>
    <a href="/cart/complete"><i class="fas fa-receipt"></i> Completed</a>
    </div>
  </div>
  <div class="col-md-9">
    <div class="table-responsive">
        <p class="text-danger"><strong>To Ship Orders</strong></p>
        <table class="table table-condensed mt-3">
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
        </table>
    </div><hr/>
    <div class="row p-2 mb-2 mr-1 ml-1 mt-n2 rounded-sm bg-gray-100 shadow-sm border">
        <div class="col-md-12">
          <p class="text-danger"><strong>Review Order Summary</strong></p>
          <span><strong>Total Items: </strong> 2</span><br/>
          <span><strong>Subtotal: </strong> ₱ 1130</span>
        </div>
    </div>
    <!--<a href="checkout" class="btn btn-sm btn-outline-danger pull-right my-2 my-sm-0 mr-1">Cancel Order</a>-->
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