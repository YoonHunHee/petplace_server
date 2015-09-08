<?php if(isset($header) && $header) echo $header;?>

<body class="nav-md">
    <div class="container body">

        <div class="main_container">

            <div class="col-md-3 left_col">
                <?php if(isset($left) && $left) echo $left;?>
            </div>

            <!-- top navigation -->
            <div class="top_nav">

                <?php if(isset($top) && $top) echo $top;?>

            </div>
            <!-- /top navigation -->


            <!-- page content -->
            <div class="right_col" role="main">

                <?php if(isset($body) && $body) echo $body;?>

            </div>
            <!-- /page content -->

        </div>

    </div>
    <script>
        NProgress.done();
    </script>
    <!-- /datepicker -->
    <!-- /footer content -->
</body>

</html>
