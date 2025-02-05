<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    /**
     * Show the home page
     *
     * @return void
     */
    public function indexAction()
    {
        $this->render('home/index.php', [
            'title' => 'Welcome to Mini MVC Framework'
        ]);
    }

    /**
     * Show the about page
     *
     * @return void
     */
    public function aboutAction()
    {
        $this->render('home/about.php', [
            'title' => 'About Us'
        ]);
    }
}
