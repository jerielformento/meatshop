<div class="container" style="padding:0 0 25px 0;">
    <ul class="nav justify-content-center">
        <li class="nav-item">
            <a class="nav-link active" href="<?php $this->getSiteUrl(); ?>products">All Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Beef</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Pork</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Chicken</a>
        </li>
    </ul>
</div>

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
  <div class="col-md-4 col-sm-6">
      <img class="card-img-top" src="<?php $this->getSiteUrl(); ?>images/slide5.jpg" alt="Card image cap" height=218 width=298>
      <div class="card-body shadow-sm">
        <div class="row">
            <p class="card-text"><span>Quantity</span></p>
            <div class="input-group mb-3 qty-group col-md-12 col-sm-12">
                <div class="input-group-prepend">
                    <button class="btn btn-dark btn-sm minus-btn"><i class="fa fa-minus"></i></button>
                </div>
                <input type="number" id="qty_input" class="form-control form-control-sm qty-input" value="1" min="1">
                <div class="input-group-prepend">
                    <button class="btn btn-dark btn-sm plus-btn"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <a href="<?php $this->getSiteUrl(); ?>cart" class="btn btn-sm btn-outline-danger my-2 my-sm-1 btn-block m-2"><i class="fa fa-cart-arrow-down"></i> Add to Cart</a>
            <a href="<?php $this->getSiteUrl(); ?>cart" class="btn btn-sm btn-outline-danger my-2 my-sm-2 btn-block m-2"><i class="fa fa-star"></i> Add to Wishlist</a>
        </div>
    </div>  
  </div>
  <div class="col-md-8 col-sm-6 mt-3">
    <h5 class="card-title text-danger">Premium Beef</h5>
    <p class="card-text"><span>&#8369; 250-300 per kilo</span></p><hr/>
    <p class="card-text"><span>Description</span></p>
    <p class="card-text"><span>Taste delicious and yummy with every bite!</span></p>
    <!--<div class="row p-2 mb-2 mr-1 ml-1 mt-n2 rounded-sm bg-gray-100 shadow-sm border">
        <div class="col-md-12">
          <p class="text-danger"><strong>Product Review</strong></p>
          <span>Taste delicious and yummy with every bite!</span>
        </div>
    </div>-->
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