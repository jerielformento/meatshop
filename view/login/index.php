<div class="row justify-content-center my-5">
      <div class="col-xl-5 col-lg-5 col-md-6">
        <div class="card o-hidden border-1 shadow-lg my-5">
          <div class="card-body p-0 border-top-danger bg-dark">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!-- <div class="col-lg-6 d-none d-lg-block bg-login-image"></div> -->
              <div class="col-lg-12">
                <div class="p-4">
                  <div class="text-center">
                    <h5 class="text-gray-500 mb-4 text-uppercase font-weight-bold"><i class="fas fa-dumpster-fire fa-lg text-danger"></i>&nbsp; JL FROZEN MEAT</h5><hr/>
                    <div id="msg-alert"><?php $this->getMessageAlert(true); ?></div>
                  </div>
                  <form class="user" action="<?php $this->getRoute('login'); ?>/userLogin" method="post" id="log-form">
                      <h5 class="text-gray-500">Login</h5><hr/>
                    <div class="input-group input-group-sm">
						<div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-user fa-lg"></i></span>
                        </div>
                        <input type="text" class="form-control" name="u-user" placeholder="Username">
                    </div>
                    <div class="input-group input-group-sm mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-unlock-alt fa-lg"></i></span>
                        </div>
                      <input type="password" class="form-control" name="u-pass" placeholder="Password">
                    </div>
                    <div class="form-group">
                    </div>
                      <div class="my-2">
                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                    </div>
					  <input class="btn btn-success btn-block m-0" type="submit" value="Login" name="sub-log">
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
<script type="text/javascript" src="<?php $this->getSiteUrl(); ?>js/custom/login.js"></script>