<?php

require_once"vendor/autoload.php";


$oResolver= new reflection\ResolverByReflection(new reflection\resolveInterfaceByName\ResolveInterfaceByNameFirstLetter());

echo "<br>Test layer0<br>";
$oClassOne=$oResolver->getInstanceOf('layer0\ClassOne');
echo $oClassOne->returnText();
echo "<br>";
$oClassTwo=$oResolver->getInstanceOf('layer0\ClassTwo');
echo $oClassTwo->returnText();
echo "<br><br>";
echo "<br>Test layer1<br>";
$oClassThree=$oResolver->getInstanceOf('layer1\ClassOne');
echo $oClassThree->returnTextDependencyLayer0();
echo "<br>";
$oClassFour=$oResolver->getInstanceOf('layer1\ClassTwo');
echo $oClassFour->returnTextDependencyLayer0();


echo "<br><br>";
echo "<br>Test layer2<br>";
$oClassFive=$oResolver->getInstanceOf('layer2\ClassOne');
echo $oClassFive->returnTextDependencyLayer1ClassOne();
echo $oClassFive->returnTextDependencyLayer1ClassTwo();
echo "<br><br>";


echo "<br>Test Passing array map <br>";
$aMap=array();

$oL0ClassOne=&new layer0\ClassOne(" <b>Text Passed to The Instance created manually layer0->ClassOne</b>");

$oL0ClassTwo=&new layer0\ClassTwo(" <b>Text Passed to The Instance created manually layer0->ClassTwo</b>");

$oL1ClassOne=&new layer1\ClassOne($oL0ClassOne);
$oL1ClassTwo=&new layer1\ClassTwo($oL0ClassTwo);

//$oL2ClassOne=&new layer2\ClassOne($oL1ClassOne,$oL1ClassTwo);

//$aMap['layer2\ClassOne']=&$oL2ClassOne;
//$aMap['layer0\ClassOne']=&$oClassOneL0;
//$aMap['layer0\IClassTwo']=$oClassTwoL0;

//$aMap['layer0\ClassOne']=&$oClassOne;

$aMap['layer1\IClassOne']=&$oL1ClassOne;
$aMap['layer1\IClassTwo']=&$oL1ClassTwo;

$oResolver2= new reflection\ResolverByReflection(new reflection\resolveInterfaceByName\ResolveInterfaceByNameFirstLetter(),$aMap);
$oClassOneFromMap=$oResolver2->getInstanceOf('layer2\ClassOne');

echo $oClassOneFromMap->returnTextDependencyLayer1ClassOne();
echo $oClassOneFromMap->returnTextDependencyLayer1ClassTwo();

