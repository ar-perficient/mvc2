<?php

class FrontController extends Config_Framework_BaseController
{
    public function frontAction()
    {
        $table = $this->model;
        $data = $table::find(1);
        $this->loadView('front', 'front');
        $this->view->set('title', $data->name);
        $this->view->set('name', $data->author);
        $this->view->render();
    }
}