
<div class="row">
	<div class="col-xl-12 col-lg-11">
	  <div class="card shadow mb-4">
		<!-- Card Header - Dropdown -->
		<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  <h6 class="m-0 font-weight-bold text-gray-600"><span class="fa fa-fw fa-edit fa-lg"></span> Edit User</h6>
		</div>
		<!-- Card Body -->
		<div class="card-body">
            <?php $form = new \REJ\Libs\Form($this->getret); ?>
            
            <form method="post" action="">
                <div><?php $this->getHeaderError(); ?></div>
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <input type="hidden" name="id" value="<?php echo $this->getret['data']['id']; ?>"/>
                         <?php
                            $form->field(array(
                                'type'=>'text',
                                'name'=>'username',
                                'label'=>'Username',
                                'placeholder'=>'Username',
                                'require'=>1,
                                'value'=>$this->saveData('username'),
                            ));
                        ?>
                            <hr/><label><medium class="text-warning">Do not edit the password and confirm password if there's no changes.</medium></label><br/>
                        <?php
                            $form->field(array(
                                'type'=>'password',
                                'name'=>'password',
                                'label'=>'Password',
                                'placeholder'=>'Password',
                                'require'=>1,
                                'value'=>'$1default'
                            ));
                            $form->field(array(
                                'type'=>'password',
                                'name'=>'confirm_password',
                                'label'=>'Confirm Password',
                                'placeholder'=>'Confirm Password',
                                'require'=>1,
                                'value'=>'$1default'
                            ));
                            $form->field(array(
                                'type'=>'text',
                                'name'=>'email_address',
                                'label'=>'Email Address',
                                'placeholder'=>'Email Address',
                                'require'=>0,
                                'value'=>$this->saveData('email_address')
                            ));
                        ?>
                    </div>

                    <div class="col-sm-6 col-md-6">
                        <?php 
                            $form->field(array(
                                'type'=>'text',
                                'name'=>'first_name',
                                'label'=>'First Name',
                                'placeholder'=>'First Name',
                                'require'=>1,
                                'value'=>$this->saveData('first_name')
                            ));
                        
                            $form->field(array(
                                'type'=>'text',
                                'name'=>'middle_name',
                                'label'=>'Middle Name',
                                'placeholder'=>'Middle Name',
                                'require'=>0,
                                'value'=>$this->saveData('middle_name')
                            ));
                        
                            $form->field(array(
                                'type'=>'text',
                                'name'=>'last_name',
                                'label'=>'Last Name',
                                'placeholder'=>'Last Name',
                                'require'=>1,
                                'value'=>$this->saveData('last_name')
                            ));
                            $form->field(array(
                                'type'=>'select',
                                'name'=>'privilege',
                                'label'=>'Privilege',
                                'require'=>1,
                                'option'=>$this->getPrivilegeList($this->getret['data']['privilege'])
                            ));
                        
                            $form->button(array(
                                'type'=>'submit',
                                'name'=>'submit',
                                'btn_class'=>'warning',
                                'class'=>'float-right mt-4',
                                'label'=>'Update Information'
                            ));
                        
                            $form->anchor(array(
                                'href'=>$this->getUrl().'/users',
                                'name'=>'submit',
                                'btn_class'=>'secondary',
                                'class'=>'float-right mt-4 mr-2',
                                'label'=>'Back'
                            ));
                        ?>
                    </div>
                </div>
            </form>
		</div>
	  </div>
	</div>
</div>                
                