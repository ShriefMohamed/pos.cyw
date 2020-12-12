<?php


namespace Framework\controllers;


use Framework\lib\AbstractController;

class InsuranceController extends AbstractController
{
    public function DefaultAction()
    {
        $this->RenderPos();
    }
}