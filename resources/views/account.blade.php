@extends('layouts.master')

@section('content')
<script>
 
</script>
<div class="content">

    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                        <div class="page-header-content">
                            <div class="page-title">
                                <h4>Store Info</h4>
                            </div>
                        </div>

                        <!-- Page content -->
                        <div class="page-content col-sm-12">
                            <!-- Form horizontal -->
                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post" action="/updateProfile">
                                        {{ csrf_field() }}
                                        <fieldset class="content-group">
                                            
                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Name</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="Name" id="name" name="name" value = "{{$store->name}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Address</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="Address" id="address" name="address" value = "{{$store->address}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Opening Hour</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="XX:XX AM" id="openingHour" name="openingHour" value = "{{$store->openingHour}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Closing Hour</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="XX:XX PM" id="closingHour" name="closingHour" value = "{{$store->closingHour}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Wait time (mins)</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="XX" id="waitingTime" name="waitingTime" value = "{{$store->waitingTime}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Website</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="Website" id="website" name="website">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Facebook Page Link</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="Facebook Page Link" id="facebookLink" name="facebookLink">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Instagram Page Link</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="Instagram Page Link" id="instagramLink" name="instagramLink">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Yelp Page Link</label>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control" placeholder="Yelp Page Link" id="yelpLink" name="yelpLink">
                                                </div>
                                            </div>

                                        </fieldset>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /form horizontal -->

                        </div>
                        <!-- /page content -->
            </div>    
        </div>
    </div>
    


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; 2017. <a href="#">Tea Era</a> by <a href="" target="_blank">Tea Era</a>
    </div>
    <!-- /footer -->

</div>    
     
@stop