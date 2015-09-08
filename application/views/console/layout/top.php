<div class="nav_menu">
    <nav class="" role="navigation">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="/assets/images/img.jpg" alt=""><?php echo $this->session->userdata('console_name')?>
                    <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                    <li>
                        <a href="/console/admin/form/<?php echo $this->session->userdata('console_id')?>">  Profile</a>
                    </li>
                    <li>
                        <a href="/console/login/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>