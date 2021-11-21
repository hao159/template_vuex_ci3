<?php

$data['template'] = $this->config->item('template');
$data['primary_nav'] = $this->config->item('primary_nav');
$data['template']['active_page'] = $this->config->item('active_page');

function dequy($aaa, &$bbb, $ccc){
    foreach ($aaa as $data) {
        if (isset($data['url'])) {
            if ( $data['url'] == $ccc) {
                $bbb =$data['name'];
                break;
            }
        }else{
            dequy($data['sub'], $bbb, $ccc);
        }
    }
}

if (!isset($page_title) and empty($page_title)) {
    // nếu không custom page title
    $active_name = '';
    dequy($data['primary_nav'], $active_name, $data['template']['active_page']);

    $data['title'] = $active_name != '' ? $active_name : get_env('APP_NAME');
}else{
   
    $data['title'] = (isset($page_title) and !empty($page_title)) ? $page_title : get_env('APP_NAME');
}


$this->load->view('template/inc/head_title', $data);
$this->load->view('template/inc/head_script', $data);
$this->load->view('template/inc/nav', $data);

?>
	<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0"><?=$data['title']?></h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url()?>">Trang chủ</a>
                                    </li>
                                    
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="content-body">
                <?php 
                	$this->load->view($temp, $data);
                 ?>

            </div>
        </div>
    </div>
    <!-- END: Content-->
<?php
$this->load->view('template/inc/footer', $data);
?>