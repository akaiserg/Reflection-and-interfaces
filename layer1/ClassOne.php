<?php

namespace layer1;
use layer0;

class ClassOne  implements IClassOne{

    private $oClassOne;


    public function __construct(layer0\IClassOne $oClassOne){


        $this->oClassOne=$oClassOne;


    }

    public function returnTextDependencyLayer0(){
      
        return $this->oClassOne->returnText();

    }

}