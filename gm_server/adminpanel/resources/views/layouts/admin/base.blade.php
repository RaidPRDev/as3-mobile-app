<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>site | Admin Dashboard</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin_design/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin_design/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin_design/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin_design/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{asset('admin_design/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin_design/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin_design/global/plugins/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin_design/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{asset('admin_design/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('admin_design/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{asset('admin_design/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('admin_design/layouts/layout/css/themes/darkblue.min.css')}}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{asset('admin_design/layouts/layout/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
        
        @yield('styles')
        
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN NOTIFICATION DROPDOWN -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img alt="" class="img-circle" src="{{asset('admin_design/layouts/layout/img/icon-color.png')}}" />
                                    <span class="username username-hide-on-mobile"> {{ Auth::user()->name }} </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <!--<li>
                                        <a href="javascript:void(0)">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>-->
                                    <li>
                                        <a href="{{ url('/admin/logout') }}">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <!--<li class="dropdown dropdown-quick-sidebar-toggler">
                                <a href="{{ url('/admin/logout') }}" class="dropdown-toggle">
                                    <i class="icon-logout"></i>
                                </a>
                            </li>-->
                            <!-- END QUICK SIDEBAR TOGGLER -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <!-- BEGIN SIDEBAR MENU -->
                        
                        @php $user = Auth::user() @endphp
                        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                            
                            <li class="nav-item start active open">
                            	<a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Dashboard</span>
                                    <span class="selected"></span>
                                    <span class="arrow open"></span>
                                </a>
                                <ul class="sub-menu">
                                    <?php /*?>
                                    @if($user->hasRole(['admin']))
                                    <li class="nav-item start {{(str_contains(Request::path(), 'admin/categories') === true)?'active open':''}}">
                                        <!--   <a href="{{route('admin.categories.index')}}" class="nav-link ">-->
                                        <a href="{{url('admin/changepassword')}}" class="nav-link ">
                                            <i class="fa fa-sitemap"></i>
                                            <span class="title">Change password</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                    @endif

                                    @if($user->hasRole(['admin']))
                                    <li class="nav-item start {{(str_contains(Request::path(), 'admin/tags') === true)?'active open':''}}">
                                        <a href="#" class="nav-link ">
                                            <i class="fa fa-tags"></i>
                                            <span class="title">change email</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                    @endif

                                     @if($user->hasRole(['admin']))
                                    <li class="nav-item start {{(str_contains(Request::path(), 'admin/tags') === true)?'active open':''}}">
                                        <a href="#" class="nav-link ">
                                            <i class="fa fa-tags"></i>
                                            <span class="title">Setting</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                    @endif
                                    <?php */?>

                                    @if($user->hasRole(['admin']))
                                    <li class="nav-item start {{(str_contains(Request::path(), 'admin/users') === true)?'active open':''}}">
                                        <a href="{{route('admin.users.index')}}" class="nav-link ">
                                            <i class="fa fa-group"></i>
                                            <span class="title">Users</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>

                                    <li class="nav-item start {{(str_contains(Request::path(), 'admin/projectTags') === true)?'active open':''}}">
                                        <a href="{{route('admin.projects.listProjectTags')}}" class="nav-link ">
                                            <i class="fa fa-group"></i>
                                            <span class="title">Project Tags</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>

                                    {{--<li class="nav-item start {{(str_contains(Request::path(), 'admin/projects') === true)?'active open':''}}">
                                        <a href="{{route('admin.projects.index')}}" class="nav-link ">
                                            <i class="fa fa-group"></i>
                                            <span class="title">Projects</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>--}}

                                    {{--<li class="nav-item start {{(str_contains(Request::path(), 'admin/projectfunds') === true)?'active open':''}}">
                                        <a href="{{route('admin.projects.projectfunds')}}" class="nav-link ">
                                            <i class="fa fa-group"></i>
                                            <span class="title">Project Funds</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>--}}

                                    {{--<li class="nav-item start {{(str_contains(Request::path(), 'admin/transactions') === true)?'active open':''}}">
                                        <a href="{{route('admin.transactions.index')}}" class="nav-link ">
                                            <i class="fa fa-group"></i>
                                            <span class="title">Transaction Logs</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>--}}

                                    <li class="nav-item start {{(str_contains(Request::path(), 'admin/settings') === true)?'active open':''}}">
                                        <a href="{{route('admin.settings.index')}}" class="nav-link ">
                                            <i class="fa fa-group"></i>
                                            <span class="title">Settings</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>

                                    <li class="nav-item start {{(str_contains(Request::path(), 'admin/groups') === true)?'active open':''}}">
                                        <a href="{{route('admin.groups.index')}}" class="nav-link ">
                                            <i class="fa fa-group"></i>
                                            <span class="title">Groups</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                    @endif
                                    
                                </ul>
                                
                            </li>
                            
                            
                        </ul>
                        <!-- END SIDEBAR MENU -->
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    @yield('content')
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
                <!-- BEGIN QUICK SIDEBAR -->
                <a href="javascript:;" class="page-quick-sidebar-toggler">
                    <i class="icon-login"></i>
                </a>
                
                <!-- END QUICK SIDEBAR -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <!-- <div class="page-footer-inner"> 2016 &copy; Earthos
                    
                </div> -->
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        <!-- BEGIN QUICK NAV -->
        
        <div class="quick-nav-overlay"></div>
        <!-- END QUICK NAV -->
        <!--[if lt IE 9]>
<script src="{{asset('admin_design/global/plugins/respond.min.js')}}"></script>
<script src="{{asset('admin_design/global/plugins/excanvas.min.js')}}"></script> 
<script src="{{asset('admin_design/global/plugins/ie8.fix.min.js')}}"></script> 
<![endif]-->
 
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{asset('admin_design/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{asset('admin_design/global/plugins/moment.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/morris/morris.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/morris/raphael-min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/counterup/jquery.waypoints.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/counterup/jquery.counterup.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/amcharts/amcharts.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/amcharts/serial.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/amcharts/pie.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/amcharts/radar.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/amcharts/themes/light.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/amcharts/themes/patterns.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/amcharts/themes/chalk.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/ammap/ammap.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/ammap/maps/js/worldLow.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/amcharts/amstockcharts/amstock.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/fullcalendar/fullcalendar.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/horizontal-timeline/horizontal-timeline.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/flot/jquery.flot.resize.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/flot/jquery.flot.categories.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jquery.sparkline.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jqvmap/jqvmap/jquery.vmap.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('admin_design/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{asset('admin_design/pages/scripts/dashboard.min.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{asset('admin_design/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/layouts/layout/scripts/demo.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/layouts/global/scripts/quick-sidebar.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('admin_design/layouts/global/scripts/quick-nav.min.js')}}" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    
    	@yield('scripts')
</body>


</html>
