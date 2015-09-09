<div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
        <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>PET PLACE</span></a>
    </div>
    <div class="clearfix"></div>

    <!-- menu prile quick info -->
    <div class="profile">
        <div class="profile_pic">
            <img src="/assets/images/img.jpg" alt="..." class="img-circle profile_img">
        </div>
        <div class="profile_info">
            <span>Welcome,</span>
            <h2><?php echo $this->session->userdata('console_name')?></h2>
        </div>
    </div>
    <!-- /menu prile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

        <div class="menu_section">
            <ul class="nav side-menu">
                <li>
                    <a href="/console/main"><i class="fa fa-tachometer"></i> Dashboard</a>
                </li>
                <li><a><i class="fa fa-lock"></i> Admins <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display: none">
                        <li><a href="/console/admin/lists">Active Admins</a>
                        </li>
                    </ul>
                </li>
                <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display: none">
                        <li><a href="/form.html">Active Users</a>
                        </li>
                        <li><a href="/form_advanced.html">Active Token</a>
                        </li>
                    </ul>
                </li>
                <li><a><i class="fa fa-map-marker"></i> Places <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display: none">
                        <li><a href="/console/place/lists">Place</a></li>
                        <li><a href="/console/course/lists">Course</a></li>
                    </ul>
                </li>
                <li><a><i class="fa fa-book"></i> Storys <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display: none">
                        <li><a href="/tables.html">Tables</a>
                        </li>
                        <li><a href="/tables_dynamic.html">Table Dynamic</a>
                        </li>
                    </ul>
                </li>
                <li><a><i class="fa fa-bar-chart-o"></i> Analysis <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display: none">
                        <li><a href="/chartjs.html">Chart JS</a>
                        </li>
                        <li><a href="/chartjs2.html">Chart JS2</a>
                        </li>
                        <li><a href="/morisjs.html">Moris JS</a>
                        </li>
                        <li><a href="/echarts.html">ECharts </a>
                        </li>
                        <li><a href="/other_charts.html">Other Charts </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- /sidebar menu -->
</div>