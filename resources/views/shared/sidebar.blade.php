<!-- Main sidebar -->
<div class="sidebar sidebar-main">
    <div class="sidebar-content">

        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">

                    <!-- Main -->
                    <li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>

                    @if(strpos(Route::getFacadeRoot()->current()->uri(), 'users')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif                    
                        <a href="/users"><i class="icon-people"></i> <span>User</span></a></li>

                    @if(strpos(Route::getFacadeRoot()->current()->uri(), 'orders')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif
                        <a href="/orders"><i class="icon-list-ordered"></i> <span>Orders</span></a></li>

                    @if(strpos(Route::getFacadeRoot()->current()->uri(), 'menu')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif
                        <a href="/menu"><i class="icon-comment-discussion"></i> <span>Menu</span></a></li>

                    @if(strpos(Route::getFacadeRoot()->current()->uri(), 'category')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif
                        <a href="/category"><i class="icon-book"></i> <span>Category</span></a></li>

                    @if(strpos(Route::getFacadeRoot()->current()->uri(), 'rewards')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif
                        <a href="/rewards"><i class="icon-comment-discussion"></i> <span>Rewards</span></a></li>


                    @if(strpos(Route::getFacadeRoot()->current()->uri(), 'promotion')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif
                        <a href="/promotion"><i class="icon-comment-discussion"></i> <span>Promotion Banner</span></a></li>

                    <!-- @if(strpos(Route::getFacadeRoot()->current()->uri(), 'bills')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif                    
                        <a href="/bills"><i class="icon-book"></i> <span>Bills</span></a></li>
                    
                    @if(strpos(Route::getFacadeRoot()->current()->uri(), 'records')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif
                        <a href="/records"><i class="icon-phone-outgoing"></i> <span>Call Records </span></a></li>

                    @if(strpos(Route::getFacadeRoot()->current()->uri(), 'dispute')!==false)
                     <li class="active">
                    @else
                     <li>
                    @endif
                        <a href="/disputes"><i class="icon-comment-discussion"></i> <span>Disputes</span></a></li>
 -->
                    <li><a ><span></span></a></li>
                    <li><a ><span></span></a></li>
                    <li><a ><span></span></a></li>
                    <li><a ><span></span></a></li>
                    <!-- /main -->

                    
                </ul>
            </div>
        </div>
        <!-- /main navigation -->

        
    </div>

    <div class="sidebar-category sidebar-category-visible about-sector">
        <div class="category-content no-padding">
            <ul class="navigation navigation-main navigation-accordion">

                <!-- Main -->
                @if(strpos(Route::getFacadeRoot()->current()->uri(), 'account')!==false)
                    <li class="active">
                @else
                    <li>
                @endif
                    <a href="/account"><i class="icon-vcard"></i> <span>Account Details</span></a></li>
              <!--   @if(strpos(Route::getFacadeRoot()->current()->uri(), 'line')!==false)
                    <li class="active">
                @else
                    <li>
                @endif
                    <a href="/phoneline"><i class="icon-iphone"></i> <span>My Phone Lines</span></a></li> -->
                <!-- /main -->

                
            </ul>
        </div>
    </div>
</div>
<!-- /main sidebar -->
