<?php
/** @var $menuModels \Illuminate\Database\Eloquent\Model[] */
/** @var $demoRoutes \Illuminate\Support\Collection|\App\Models\DungeonRoute[] */
/** @var $isProduction string */
/** @var $isMobile boolean */
/** @var $version string */
/** @var $nameAndVersion string */
/** @var $theme string */
/** @var $menuModelEdit \Illuminate\Database\Eloquent\Model */

$user = \Illuminate\Support\Facades\Auth::user();
// Show the legal modal or not if people didn't agree to it yet
$showLegalModal = isset($showLegalModal) ? $showLegalModal : true;
// Custom content or not
$custom = isset($custom) ? $custom : false;
// Wide mode or not (only relevant if custom = false)
$wide = isset($wide) ? $wide : false;
// Show header or not
$header = isset($header) ? $header : true;
// Show footer or not
$footer = isset($footer) ? $footer : true;
// Setup the title
$title = isset($title) ? $title . ' - ' : '';
// Any additional parameters to pass to the login/register blade
$loginParams = isset($loginParams) ? $loginParams : [];
$registerParams = isset($registerParams) ? $registerParams : [];
// Show cookie consent
$cookieConsent = isset($cookieConsent) ? $cookieConsent : true;
// If user already approved of the cookie..
if (isset($_COOKIE['cookieconsent_status']) && $_COOKIE['cookieconsent_status'] === 'dismiss') {
    // Don't bother the user with it anymore
    $cookieConsent = false;
}
$devCacheBuster = config('app.env') === 'local' ? '?t=' . time() : '';
// Show ads if not set
$showAds = isset($showAds) ? $showAds : true;
// Analytics or not, default = $isProduction
$analytics = isset($analytics) ? $analytics : $isProduction;

$rootClass = isset($rootClass) ? $rootClass : '';
?><!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="theme {{$theme}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title . config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app-' . $version . '.css') . $devCacheBuster }}" rel="stylesheet">
    <link href="{{ asset('css/custom-' . $version . '.css') . $devCacheBuster }}" rel="stylesheet">
    <link href="{{ asset('css/lib-' . $version . '.css') . $devCacheBuster }}" rel="stylesheet">
    <link href="{{ asset('css/theme-' . $version . '.css') . $devCacheBuster }}" rel="stylesheet">
    <link href="{{ asset('css/home-' . $version . '.css') . $devCacheBuster }}" rel="stylesheet">
    <link rel="icon" href="/images/icon/favicon.ico">
    @yield('head')

    @include('common.general.inlinemanager')
    @include('common.general.inline', ['path' => 'layouts/app', 'section' => false, 'options' => ['guest' => Auth::guest()]])
    @include('common.general.sitescripts', ['showLegalModal' => $showLegalModal])

    @if($header)
        @include('common.general.inline', ['path' => 'common/general/navbarshrink'])
    @endif

    @isset($menuItems)
        @include('common.general.inline', ['path' => 'common/general/menuitemsanchor'])
    @endisset

    @if($cookieConsent)
        @include('common.thirdparty.cookieconsent')
    @endif

    @if($showAds)
        @include('common.thirdparty.ads')
    @endif
    @if($analytics)
        @include('common.thirdparty.analytics')
    @endif
</head>
<body>
<div id="app">
    @if($header)
        @include('layouts.header')
    @endif

    @if($custom)
        @empty($rootClass)
            @yield('content')
        @else
            <div class="{{$rootClass}}">
                @yield('content')
            </div>
        @endisset

    @elseif(isset($menuItems))
        <div class="container container_wide mt-3">
            <div class="row">
                <div class="col-xl-3 bg-secondary p-3">
                    <h4>{{ $menuTitle }}</h4>
                    <hr>
                    @isset($menuModels)
                        <select id="selected_model_id" class="form-control selectpicker">
                            @foreach($menuModels as $menuModel)
                                @php($hasIcon = isset($menuModel->iconfile))
                                <option
                                    data-url="{{ route($menuModelsRoute, [$menuModelsRouteParameterName => $menuModel->getRouteKey()]) }}"
                                    @if($hasIcon)
                                    data-content="<img src='{{ url('storage/' . $menuModel->iconfile->path) }}' style='max-height: 16px;'/> {{ $menuModel->name }}"
                                    @endif
                                    {{ $menuModelEdit->getKey() === $menuModel->getKey() ? 'selected' : '' }}
                                >{{ $hasIcon ? '' : $menuModel->name }}</option>
                            @endforeach
                        </select>
                        <hr>
                    @endisset
                    <ul class="nav flex-column nav-pills">
                        @foreach($menuItems as $index => $menuItem)
                            <li class="nav-item">
                                <a class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                   data-toggle="tab" href="{{ $menuItem['target'] }}" role="tab"
                                   aria-controls="routes" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                    <i class="fas {{ $menuItem['icon'] }}"></i> {{ $menuItem['text'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-xl-9 bg-secondary ml-0 mt-xl-0 mt-3 p-3">
                    @yield('content')
                </div>
            </div>
        </div>

    @else

        @if (!$isProduction && (!Auth::check() || !$user->hasRole('admin')))
            <div class="container-fluid alert alert-warning text-center mt-4">
                <i class="fa fa-exclamation-triangle"></i>
                {{ __('Warning! You are currently on the staging environment of Keystone.guru. This is NOT the main site.') }}
                <br>
                <a href="https://keystone.guru/">{{ __('Take me to the main site!') }}</a>
            </div>
        @endif

        @yield('global-message')

        @if( $showAds && !$isMobile)
            <div align="center" class="mt-4">
                @include('common.thirdparty.adunit', ['id' => 'site_top_header', 'type' => 'header', 'reportAdPosition' => 'top-right'])
            </div>
        @endif

        <div class="container-fluid">
            <div class="row">
                <div class="{{ $wide ? "flex-fill ml-lg-3 mr-lg-3" : "col-md-8 offset-md-2" }}">
                    <div class="card mt-3 mb-3">
                        <div class="card-header {{ $wide ? "panel-heading-wide" : "" }}">
                            <div class="row">
                                @hasSection('header-addition')
                                    <div class="col text-center">
                                        <h4>@yield('header-title')</h4>
                                    </div>
                                    <div class="ml-auto">
                                        @yield('header-addition')
                                    </div>
                                @else
                                    <div class="col-lg-12 text-center">
                                        <h4>@yield('header-title')</h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @include('common.general.messages')

                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if( $footer )

        @if( $showAds )
            <div align="center" class="mt-4">
                @include('common.thirdparty.adunit', ['id' => 'site_bottom_header', 'type' => 'footer'])
            </div>
        @endif


        <div class="home">
            <section class="footer1 cid-soU7JptK9v" once="footers" id="footer1-m">


                <div class="container">
                    <div class="row mbr-white">
                        <div class="col-12 col-md-6 col-lg-3">
                            <h5 class="mbr-section-subtitle mbr-fonts-style mb-2 display-7">
                                <strong>About</strong></h5>
                            <ul class="list mbr-fonts-style display-4">
                                <li class="mbr-text item-wrap">
                                    <a href="{{ route('misc.credits') }}">{{ __('Credits') }}</a>
                                </li>
                                <li class="mbr-text item-wrap">
                                    <a href="{{ route('misc.about') }}">{{ __('About') }}</a>
                                </li>
                                <li class="mbr-text item-wrap"><br></li>
                                <li class="mbr-text item-wrap"><br></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <h5 class="mbr-section-subtitle mbr-fonts-style mb-2 display-7">
                                <strong>External</strong></h5>
                            <ul class="list mbr-fonts-style display-4">

                                <li class="mbr-text item-wrap">
                                    <a href="https://www.patreon.com/keystoneguru" target="_blank">
                                        <i class="fab fa-patreon"></i> {{ __('Patreon') }}
                                    </a>
                                </li>
                                <li class="mbr-text item-wrap">
                                    <a href="https://discord.gg/2KtWrqw" target="_blank">
                                        <i class="fab fa-discord"></i> {{ __('Discord') }}
                                    </a>
                                </li>
                                <li class="mbr-text item-wrap">
                                    <a href="https://github.com/Wotuu/keystone.guru" target="_blank">
                                        <i class="fab fa-github"></i> {{ __('Github') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <h5 class="mbr-section-subtitle mbr-fonts-style mb-2 display-7">
                                <strong>Legal</strong></h5>
                            <ul class="list mbr-fonts-style display-4">
                                <li class="mbr-text item-wrap">
                                    <a href="{{ route('legal.terms') }}">{{ __('Terms of Service') }}</a>
                                </li>
                                <li class="mbr-text item-wrap">
                                    <a href="{{ route('legal.privacy') }}">{{ __('Privacy Policy') }}</a>
                                </li>
                                <li class="mbr-text item-wrap">
                                    <a href="{{ route('legal.cookies') }}">{{ __('Cookies Policy') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <h5 class="mbr-section-subtitle mbr-fonts-style mb-2 display-7">Trademark</h5>
                            <p class="mbr-text mbr-fonts-style mb-4 display-4">
                                World of Warcraft, Warcraft and Blizzard Entertainment are trademarks or registered
                                trademarks
                                of Blizzard Entertainment, Inc. in the U.S. and/or other countries. This website is not
                                affiliated with Blizzard Entertainment.</p>
                            <h5 class="mbr-section-subtitle mbr-fonts-style mb-3 display-7">
                                <strong>Social</strong>
                            </h5>
                            <div class="social-row display-7">
                                <div class="soc-item">
                                    <a href="https://www.youtube.com/channel/UCtjlNmuS2kVQhNvPdW5D2Jg" target="_blank">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </div>
                                <div class="soc-item">
                                    <a href="https://twitter.com/keystoneguru" target="_blank">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </div>
                                <div class="soc-item">
                                    <a href="https://reddit.com/r/KeystoneGuru" target="_blank">
                                        <i class="fab fa-reddit"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <p class="mbr-text mb-0 mbr-fonts-style copyright align-center display-7">
                                ©{{ date('Y') }} {{ $nameAndVersion }} - {{ __('All Rights Reserved') }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endif
</div>

@auth
    @if(!$user->legal_agreed)
        @component('common.general.modal', ['id' => 'legal_modal', 'static' => true])
            @include('common.modal.legal')
        @endcomponent
    @endif
@endauth

@if($header)
    <!-- Modal create route -->
    @component('common.general.modal', ['id' => 'create_route_modal', 'size' => 'xl'])
        @include('common.modal.createroute')
    @endcomponent
    <!-- END modal create route -->
@endif

@guest
    <!-- Modal login -->
    @component('common.general.modal', ['id' => 'login_modal', 'class' => 'login-modal-dialog'])
        @include('common.forms.login', array_merge(['modal' => true], $loginParams))
    @endcomponent
    <!-- END modal login -->

    <!-- Modal register -->
    @component('common.general.modal', ['id' => 'register_modal', 'class' => 'register-modal-dialog'])
        @include('common.forms.register', array_merge(['modal' => true], $registerParams))
    @endcomponent
    <!-- END modal register -->
@endguest

<!-- Scripts -->
<script src="{{ asset('js/app-' . $version . '.js') . $devCacheBuster }}"></script>
<?php // Compiled only in production, otherwise include all files as-is to prevent having to recompile everything all the time ?>
<script src="{{ asset('js/custom-' . $version . '.js') .$devCacheBuster }}"></script>
<script src="{{ asset('js/lib-' . $version . '.js') . $devCacheBuster }}"></script>
@yield('scripts')
<script type="application/javascript">
    $(function () {
        // Do this once and not a bunch of times for all different elements
        refreshSelectPickers();
        // All layers have been fetched and everything rebuilt, refresh tooltips for all elements
        refreshTooltips();
    });
</script>
</body>
</html>
