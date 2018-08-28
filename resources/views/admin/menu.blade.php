@php
    $auth_user = \Illuminate\Support\Facades\Auth::user();
@endphp

<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> @lang('app.dashboard')</a>
            </li>

            <li>
                <a href="#"><i class="fa fa-bullhorn"></i> @lang('app.my_campaigns')<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>  <a href="{{route('my_campaigns')}}">@lang('app.my_campaigns')</a> </li>
                    <li>  <a href="{{route('start_campaign')}}">@lang('app.start_a_campaign')</a> </li>
                    <li>  <a href="{{route('my_pending_campaigns')}}">@lang('app.pending_campaigns')</a> </li>
                </ul>
            </li>
			
			<li>
                <a href="#"><i class="fa fa-bullhorn"></i> @lang('app.my_fundraisers')<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>  <a href="{{route('my_fundraisers')}}">@lang('app.my_fundraisers')</a> </li>
                    <li>  <a href="{{route('start_fundraiser')}}">@lang('app.start_a_fundraiser')</a> </li>
                </ul>
            </li>

            @if($auth_user->is_admin())
                <li> <a href="{{ route('categories') }}"><i class="fa fa-folder-o"></i> @lang('app.categories')</a>  </li>
				<li>
                    <a href="#"><i class="fa fa-bullhorn"></i> @lang('app.fundraisers')<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li> <a href="{{ route('all_fundraisers') }}">@lang('app.all_fundraisers')</a> </li>
                        <li>  <a href="{{route('start_fundraiser')}}">@lang('app.start_a_fundraiser')</a> </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-bullhorn"></i> @lang('app.campaigns')<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li> <a href="{{ route('all_campaigns') }}">@lang('app.all_campaigns')</a> </li>
                        <li> <a href="{{ route('staff_picks') }}">@lang('app.staff_picks')</a> </li>
                        <li> <a href="{{ route('funded') }}">@lang('app.funded')</a> </li>
                        <li> <a href="{{ route('blocked_campaigns') }}">@lang('app.blocked_campaigns')</a> </li>
                        <li> <a href="{{ route('pending_campaigns') }}">@lang('app.pending_campaigns')</a> </li>
                        <li> <a href="{{ route('expired_campaigns') }}">@lang('app.expired_campaigns')</a> </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>

                <li>
                    <a href="#"><i class="fa fa-wrench fa-fw"></i> @lang('app.settings')<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li> <a href="{{ route('general_settings') }}">@lang('app.general_settings')</a> </li>
                        <li> <a href="{{ route('payment_settings') }}">@lang('app.payment_settings')</a> </li>
                        <li> <a href="{{ route('theme_settings') }}">@lang('app.theme_settings')</a> </li>
                        <li> <a href="{{ route('social_settings') }}">@lang('app.social_settings')</a> </li>
                        <li> <a href="{{ route('re_captcha_settings') }}">@lang('app.re_captcha_settings')</a> </li>
                        <li> <a href="{{ route('other_settings') }}">@lang('app.other_settings')</a> </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li> <a href="{{ route('pages') }}"><i class="fa fa-file-word-o"></i> @lang('app.pages')</a>  </li>

                <li> <a href="{{route('users')}}"><i class="fa fa-users"></i> @lang('app.users')</a>  </li>
                <li> <a href="{{route('withdrawal_requests')}}"><i class="fa fa-balance-scale"></i> @lang('app.withdrawal_requests')</a>  </li>
            @endif

            <li> <a href="{{route('payments')}}"><i class="fa fa-money"></i> @lang('app.payments')</a>  </li>
            <!--<li> <a href="{{route('withdraw')}}"><i class="fa fa-credit-card"></i> @lang('app.withdraw')</a>  </li>-->
            <li> <a href="{{route('withdrawal_preference')}}"><i class="fa fa-credit-card"></i> @lang('app.withdraw')</a>  </li>
            <li> <a href="{{route('profile')}}"><i class="fa fa-user"></i> @lang('app.profile')</a>  </li>
            <li> <a href="{{route('change_password')}}"><i class="fa fa-lock"></i> @lang('app.change_password')</a>  </li>

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
