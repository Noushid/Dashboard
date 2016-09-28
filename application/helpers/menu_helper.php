<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 18/7/16
 * Time: 5:06 PM
 */

function dashboard_menu($current)
{
    $menu = [
        'dashboard' => [
            'title' => 'dashboard',
            'icon' => 'fa-tachometer',
            'link' => ''
        ],
        'team' => [
            'title' => 'team',
            'icon' => 'fa-users',
            'link' => 'employees'
        ],
        'portfolio' => [
            'title' => 'portfolio',
            'icon' => 'fa-folder',
            'link' => 'portfolio'
        ],
        'testimonial' => [
            'title' => 'testimonial',
            'icon' => 'fa-thumbs-o-up',
            'link' => 'testimonial'
        ],
        'gallery' => [
            'title' => 'gallery',
            'icon' => 'fa-picture-o',
            'link' => 'gallery'
        ]
    ];


    $html = '';

    $html = '<nav class="sidebar-left">
        <div class="">
          <ul class="menu-left">
            <li>
              <div class="user-img">
                <img class="img-responsive img-circle center-block" src="'.base_url('img/aju.png').'" alt="User">
              </div>
              <div class="user-id text-center">
                <span class="">Ajeeb</span>
              </div>
            </li>';
    foreach ($menu as $key => $value) {
        if ($key == $current) {
            $html .= '<li><a href="' . base_url('#'.$value['link']) . '" class="active">'.ucfirst($value['title']).' &nbsp;<i class="menu-icon fa '.$value['icon'].' pull-right"></i></a></li>';

        } else {
            $html .= '<li><a href="' . base_url('#'.$value['link']) . '" class="">'.ucfirst($value['title']).' &nbsp;<i class="menu-icon fa '.$value['icon'].' pull-right"></i></a></li>';
        }
    }

    $html .='</ul>
        </div>
      </nav>';

    return $html;
}
