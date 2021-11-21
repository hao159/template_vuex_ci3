<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern menu-expanded navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl navbar-dark bg-danger">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php
                            $user = $this->session->userdata('UserName');
                            $name = isset($_SESSION['FullName']) ? $this->session->userdata('FullName') : $this->session->userdata('UserName');
                        ?>
                        <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder"><?=$name?></span><span class="user-status"><?=$user?></span></div><span class="avatar"><img class="round" src="<?= base_url() ?>public/app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="/logout"><i class="mr-50" data-feather="power"></i> Đăng xuất</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="#!"><span class="brand-logo">
                            <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                                            <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg></span>
                        <h2 class="brand-text">Demo</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <?php 
                    if ($primary_nav) {
                        foreach ($primary_nav as $key => $nav_1st) {
                            // code...
                            $link_class = '';
                            $li_active  = '';
                            $menu_link  = '';
                            // Get 1st level link's vital info
                            if (isset($nav_1st['url']) and $nav_1st['url']) {
                                if ($nav_1st['url'] == 'header') {
                                    $url = 'header';
                                }else{
                                    $url = base_url().$nav_1st['url'];
                                }
                            }else{
                                $url = '';
                            }


                            $active     = (isset($nav_1st['url']) && ($template['active_page'] == $nav_1st['url'])) ? ' active' : '';
                            #<i data-feather="home"></i>
                            $icon       = (isset($nav_1st['icon']) && $nav_1st['icon']) ? '<i data-feather="' . $nav_1st['icon'] . '"></i>' : '<i data-feather="circle"></i>';

                            // Check if the nav_1st has a submenu
                            if (isset($nav_1st['sub']) && $nav_1st['sub']) {
                                // Since it has a submenu, we need to check if we have to add the class active
                                // to its parent li element (only if a 2nd or 3rd level nav_1st is active)
                                foreach ($nav_1st['sub'] as $sub_link) {
                                    if (in_array($template['active_page'], $sub_link)) {
                                        $li_active = ' open';
                                        break;
                                    }

                                    // 3rd level links
                                    if (isset($sub_link['sub']) && $sub_link['sub']) {
                                        foreach ($sub_link['sub'] as $sub2_link) {
                                            if (in_array($template['active_page'], $sub2_link)) {
                                                $li_active = ' open';
                                                break;
                                            }
                                        }
                                    }
                                }

                                $menu_link = ' nav-item has-sub';
                            }else{
                                $menu_link = ' nav-item ';
                            }
                            // Create the class attribute for our link
                            if ($menu_link || $active) {
                                $link_class = $menu_link . $active ;
                            }


                            if ($url == 'header') {
                                // header
                                echo '<li class=" navigation-header"><span>'.$nav_1st['name'].'</span><i data-feather="more-horizontal"></i></li>';
                            }else{
                                //link"
                                echo '<li class="'.$link_class. $li_active.' ">';
                                    echo '<a href="'.$url.'" class="d-flex align-items-center">'.$icon;
                                    
                                    echo '<span class="menu-title text-truncate">'.$nav_1st['name'].'</span>';
                                    echo '</a>';
                                    if (isset($nav_1st['sub']) && $nav_1st['sub']) {
                                        echo '<ul class="menu-content">';
                                        foreach ($nav_1st['sub'] as $key => $nav_2nd) {
                                            // code...
                                            $link_class = '';
                                            $li_active  = '';
                                            $menu_link  = '';
                                            // Get 2nd level link's vital info
                                            $url        = (isset($nav_2nd['url']) && $nav_2nd['url']) ? base_url().$nav_2nd['url'] : '#';

                                            $active     = (isset($nav_2nd['url']) && ($template['active_page'] == $nav_2nd['url'])) ? ' active' : '';
                                            $icon       = (isset($nav_2nd['icon']) && $nav_2nd['icon']) ? '<i data-feather="' . $nav_2nd['icon'] . '"></i>' : '<i data-feather="circle"></i>';

                                            // Check if the nav_2nd has a submenu
                                            if (isset($nav_2nd['sub']) && $nav_2nd['sub']) {
                                                // Since it has a submenu, we need to check if we have to add the class active
                                                // to its parent li element (only if a 2nd or 3rd level nav_2nd is active)
                                                foreach ($nav_2nd['sub'] as $sub_link) {
                                                    if (in_array($template['active_page'], $sub_link)) {
                                                        $li_active = ' open ';
                                                        break;
                                                    }

                                                    // 3rd level links
                                                    if (isset($sub_link['sub']) && $sub_link['sub']) {
                                                        foreach ($sub_link['sub'] as $sub2_link) {
                                                            if (in_array($template['active_page'], $sub2_link)) {
                                                                $li_active = ' open ';
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }

                                                $menu_link = 'has-sub';
                                            }else{
                                                $menu_link = '';
                                            }
                                            // Create the class attribute for our link
                                            if ($menu_link || $active) {
                                                $link_class = $menu_link . $active ;
                                            }

                                            echo '<li class="'.$link_class.' '.$li_active .' ">';
                                            echo '<a href="'.$url.'" class="d-flex align-items-center">'.$icon;
                                                
                                                echo '<span class="menu-title text-truncate">'.$nav_2nd['name'].'</span>';
                                                
                                            echo '</a>';
                                            if (isset($nav_2nd['sub']) && $nav_2nd['sub']) {
                                                echo '<ul class="menu-content">';
                                                    foreach ($nav_2nd['sub'] as $key => $nav_3rd) {
                                                        $url    = (isset($nav_3rd['url']) && $nav_3rd['url']) ? base_url().$nav_3rd['url'] : '#';
                                                        $active     = (isset($nav_3rd['url']) && ($template['active_page'] == $nav_3rd['url'])) ? ' active' : '';
                                                        // code...
                                                        echo '<li class="'. $active .'">';
                                                        echo '<a class="d-flex align-items-center" href="'.$url.'"><span class="menu-item text-truncate">'.$nav_3rd['name'].'</span></a>';
                                                        echo '</li>';
                                                    }
                                                echo '</ul>';
                                            }
                                            echo '</li>';

                                        }


                                        echo '</ul>';
                                    }

                                echo '</li>';
                            }
                        }
                    }

                 ?>
                
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->