<!-- START MAIN CONTENT -->
<div class="main_content crop-avatar">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="ps-widget__header">
                        <form id="avatar-upload-form" enctype="multipart/form-data" action="javascript:void(0)" onsubmit="return false">
                            <div class="avatar-upload-container">
                                <div id="account-avatar">
                                    <div class="profile-image">
                                        <div class="avatar-view mt-card-avatar">
                                            <img class="br2" src="{{ auth('customer')->user()->avatar_url }}" alt="{{ auth('customer')->user()->name }}" width="150">
                                            <div class="mt-overlay br2">
                                                <span><i class="linearicons-pencil"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div class="dashboard_menu">
                        <ul class="nav nav-tabs flex-column" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if (Route::currentRouteName() == 'customer.overview') active @endif" href="{{ route('customer.overview') }}"><i class="ti-layout-grid2"></i>{{ __('Overview') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (Route::currentRouteName() == 'customer.orders' || Route::currentRouteName() == 'customer.orders.view') active @endif" href="{{ route('customer.orders') }}"><i class="ti-shopping-cart-full"></i>{{ __('Orders') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (Route::currentRouteName() == 'customer.address' || Route::currentRouteName() == 'customer.address.create' || Route::currentRouteName() == 'customer.address.edit') active @endif" href="{{ route('customer.address') }}"><i class="ti-location-pin"></i>{{ __('My Addresses') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (Route::currentRouteName() == 'customer.edit-account') active @endif" href="{{ route('customer.edit-account') }}"><i class="ti-id-badge"></i>{{ __('Account details') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (Route::currentRouteName() == 'customer.change-password') active @endif" href="{{ route('customer.change-password') }}"><i class="ti-id-badge"></i>{{ __('Change password') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.logout') }}"><i class="ti-lock"></i>{{ __('Logout') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="dashboard_content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP -->
    <div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" aria-labelledby="avatar-modal-label"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="avatar-form" method="post" action="{{ route('customer.avatar') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title" id="avatar-modal-label"><i class="til_img"></i><strong>{{ __('Profile Image') }}</strong></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="avatar-body">

                            <!-- Upload image and data -->
                            <div class="avatar-upload">
                                <input class="avatar-src" name="avatar_src" type="hidden">
                                <input class="avatar-data" name="avatar_data" type="hidden">
                                {!! csrf_field() !!}
                                <label for="avatarInput">{{ __('New image') }}</label>
                                <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                            </div>

                            <!-- Crop and preview -->
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="avatar-wrapper"></div>
                                    <div class="error-message text-danger" style="display: none"></div>
                                </div>
                                <div class="col-md-3 avatar-preview-wrapper">
                                    <div class="avatar-preview preview-lg"></div>
                                    <div class="avatar-preview preview-md"></div>
                                    <div class="avatar-preview preview-sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark btn-sm" type="button" data-dismiss="modal">{{ __('Close') }}</button>
                        <button class="btn btn-fill-out btn-sm avatar-save" type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal -->
</div>
