<?php
function getNavbar($depth){
    $root_dir = "";
    for($i = 0; $i < $depth; $i++){
        $root_dir = $root_dir.'../' ;
    }

    $navbar = "
    <!-- Navigation -->
    <nav class='navbar navbar-default navbar-custom navbar-fixed-top'>
        <div class='container-fluid'>
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class='navbar-header page-scroll'>
                <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
                    <span class='sr-only'>Toggle navigation</span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </button>
                <a class='navbar-brand' href='mailto:kablaa@hackmy.world'>Contact</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
                <ul class='nav navbar-nav navbar-right'>
                    <li>
                        <a href=".$root_dir."index.php>Home</a>
                    </li>
                    <li>
                        <a href=".$root_dir."writeups/>Writeups</a>
                    </li>
                    <li>
                         <a href=".$root_dir."projects/>Projects</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    ";
    return $navbar;
}
?>
