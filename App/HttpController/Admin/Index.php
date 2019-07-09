<?php

namespace App\HttpController\Admin;

use App\Base\AdminController;

class Index extends AdminController
{
    public function index()
    {
        $this->render('admin.index');
    }

    public function indexContext()
    {
        $this->render('admin.indexContext');
    }
}
