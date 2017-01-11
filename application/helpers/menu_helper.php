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
                <img class="img-responsive img-circle center-block" src="'.base_url('assets/img/logo/psybo.png').'" alt="User">
              </div>
              <div class="user-id text-center">
                <span class="">Psybo Technologies</span>
              </div>
            </li>';
    foreach ($menu as $key => $value) {
        if ($key == $current) {
            $html .= '<li><a href="' . base_url('admin/#'.$value['link']) . '" class="active">'.ucfirst($value['title']).' &nbsp;<i class="menu-icon fa '.$value['icon'].' pull-right"></i></a></li>';

        } else {
            $html .= '<li><a href="' . base_url('admin/#'.$value['link']) . '" class="">'.ucfirst($value['title']).' &nbsp;<i class="menu-icon fa '.$value['icon'].' pull-right"></i></a></li>';
        }
    }

    $html .='</ul>
        </div>
      </nav>';

    return $html;
}


function menu($current)
{
    $menu = [
        'home' => [
            'title' => 'Home',
            'icon' => '',
            'link' => 'home'
        ],
        'about' => [
            'title' => 'About',
            'icon' => '',
            'link' => 'about'
        ],
        'portfolio' => [
            'title' => 'Portfolio',
            'icon' => '',
            'link' => 'portfolios'
        ],
        'teams' => [
            'title' => 'Teams',
            'icon' => '',
            'link' => 'team'
        ],
        'Blog' => [
            'title' => 'Blog',
            'icon' => '',
            'link' => 'blog'
        ],
        'contact' => [
            'title' => 'Contact',
            'icon' => '',
            'link' => 'contact'
        ]
    ];

    $html = '';
    foreach ($menu as $key=>$value) {
        if ($key == $current) {
            $html .= '<li><a class="active" href="' . base_url($value['link']) . '">' . $key . '</a></li>';
        }else{
            $html .= '<li><a href="' . base_url($value['link']) . '">' . $key . '</a></li>';
        }
    }
    return $html;
}

function footer_menu()
{
    $menu = [
        'Home' => [
            'title' => 'Home',
            'icon' => '',
            'link' => 'home'
        ],
        'About' => [
            'title' => 'About',
            'icon' => '',
            'link' => 'about'
        ],
        'Service' => [
            'title' => 'Service',
            'icon' => '',
            'link' => 'about#skill-service'
        ],
        'Portfolio' => [
            'title' => 'Portfolio',
            'icon' => '',
            'link' => 'portfolios'
        ],
        'Recent Works' => [
            'title' => 'recentwork',
            'icon' => '',
            'link' => 'home#/recentwork'
        ],
        'Our Blogs' => [
            'title' => 'blog',
            'icon' => '',
            'link' => 'blog'
        ],
        'Our Teams' => [
            'title' => 'Teams',
            'icon' => '',
            'link' => 'team'
        ],
        'Get Connect' => [
            'title' => 'Contact',
            'icon' => '',
            'link' => 'contact#contact'
        ]
    ];

    $html = '';
    foreach ($menu as $key => $value) {
        $html .= '<li><a href="' . $value['link'] . '">' . $key . '</a></li>';
    }
    return $html;
}


function sndfooter_menu()
{
    $menu = [
        'View our flare' => [
            'title' => 'flare',
            'icon' => '',
            'link' => 'flare'
        ],
        'View our 2016 Achievements' => [
            'title' => 'achievements',
            'icon' => '',
            'link' => 'achievements'
        ],
        'View Gallery' => [
            'title' => 'gallery',
            'icon' => '',
            'link' => 'blog#portfolioGrid'
        ],
        'Subscribe me' => [
            'title' => 'newsletter',
            'icon' => '',
            'link' => 'about#newsletter'
        ]
    ];

    $html = '';
    foreach ($menu as $key => $value) {
        $html .= '<li><a href="' . $value['link'] . '">' . $key . '</a></li>';
    }
    return $html;
}
