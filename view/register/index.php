<div class="row">
  <div class="col-md-12">
  <div class="row p-2 mb-2 mr-1 ml-1 mt-2 rounded-sm border shadow-sm">
        <div class="col-md-12 pt-2">
          <h5 class="text-danger"><i class="fa fa-user mr-1"></i> Account Registration</h5><hr/>
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-circle"></i> You must register an account before proceeding to checkout our products.
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" autocomplete="off">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mobile Number</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="09xxxxxxxxx" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">First Name</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="First Name" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">First Name</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Last Name" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Address</label>
                        <textarea class="form-control" id="exampleInputEmail1" placeholder="Enter address" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <small>By clicking Create Your Account, you are agree to our <a href="#">Terms of use<a> and <a href="#">Data Policy</a>.</small>

                    </div>
                </div>
            </div><hr/>
            <button class="btn btn-sm btn-outline-danger pull-right my-2 my-sm-0 mr-1">Create Your Account</button>
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