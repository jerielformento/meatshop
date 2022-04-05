<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-11">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-dark"><i class="fa fa-list"></i> Product List </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row table-responsive">
                    <button class="btn btn-sm btn-danger my-2 my-sm-0 ml-1"><i class="fa fa-plus"></i> Add Category</button>
                    <button class="btn btn-sm btn-danger my-2 my-sm-0 ml-1"><i class="fa fa-plus"></i> Add Product</button>
                    <table class="table table-hover table-condensed mt-3">
                        <thead>
                            <tr>
                                <th width=100>Image</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th width=148>Category</th>
                                <th width=100>Price</th>
                                <th width=100>Stock</th>
                                <th width=150>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><img class="card-img-top" src="<?php $this->getSiteUrl(); ?>images/slide2.jpg" alt="Card image cap" style="height:70px; width:70px;"></td>
                                <td style="max-width:170px;">Beef Premium</td>
                                <td style="max-width:170px;">Delicious and yummy!</td>
                                <td style="max-width:170px;"><span class="badge badge-danger">Beef</span></td>
                                <td style="max-width:170px;">₱380</td>
                                <td style="max-width:170px;"><span class="badge badge-secondary">Unlimited</span></td>
                                <td style="max-width:150;">
                                    <button class="btn btn-sm btn-info my-2 my-sm-0 mr-1"><i class="fa fa-info-circle"></i></button>
                                    <button class="btn btn-sm btn-warning my-2 my-sm-0 mr-1"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger my-2 my-sm-0 mr-1"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><img class="card-img-top" src="<?php $this->getSiteUrl(); ?>images/slide1.jpg" alt="Card image cap" style="height:70px; width:70px;"></td>
                                <td style="max-width:170px;">Samgyupsal Meat Premium</td>
                                <td style="max-width:170px;">Fresh and frozen!</td>
                                <td style="max-width:170px;"><span class="badge badge-danger">Beef</span></td>
                                <td style="max-width:170px;">₱750</td>
                                <td style="max-width:170px;"><span class="badge badge-secondary">Unlimited</span></td>
                                <td style="max-width:150;">
                                    <button class="btn btn-sm btn-info my-2 my-sm-0 mr-1"><i class="fa fa-info-circle"></i></button>
                                    <button class="btn btn-sm btn-warning my-2 my-sm-0 mr-1"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger my-2 my-sm-0 mr-1"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><img class="card-img-top" src="<?php $this->getSiteUrl(); ?>images/slide6.jpg" alt="Card image cap" style="height:70px; width:70px;"></td>
                                <td style="max-width:170px;">Whole Chicken</td>
                                <td style="max-width:170px;">Awesome taste!</td>
                                <td style="max-width:170px;"><span class="badge badge-success">Chicken</span></td>
                                <td style="max-width:170px;">₱250</td>
                                <td style="max-width:170px;"><span class="badge badge-secondary">37</span></td>
                                <td style="max-width:150;">
                                    <button class="btn btn-sm btn-info my-2 my-sm-0 mr-1"><i class="fa fa-info-circle"></i></button>
                                    <button class="btn btn-sm btn-warning my-2 my-sm-0 mr-1"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger my-2 my-sm-0 mr-1"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="ml-2">Total count:<span class="ml-1 badge badge-danger">3 items</span></p>
                </div>  
            </div>
        </div>
    </div>
</div>