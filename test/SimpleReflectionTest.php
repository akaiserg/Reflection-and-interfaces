<?php



class SimpleReflectionTest extends PHPUnit_Framework_TestCase {


    private $oResolver;
    private $sTxt="Test text";


    /**
     *called before the test functions will be executed
     *this function is defined in PHPUnit_TestCase and overwritten
     *here
     */
    function setUp() {

        $this->oResolver= new reflection\ResolverByReflection(new reflection\resolveInterfaceByName\ResolveInterfaceByNameFirstLetter());
        $aMap=array();
        $oL0ClassOne=&new layer0\ClassOne(" <b>Text Passed to The Instance created manually layer0->ClassOne</b>");
        $aMap['layer2\ClassOne']=&$oL0ClassOne;
        //$this->oResolverWithMap= new reflection\ResolverByReflection(new reflection\resolveInterfaceByName\ResolveInterfaceByNameFirstLetter(),);

    }

    /**
     * called after the test functions are executed
    * this function is defined in PHPUnit_TestCase and overwritten
    * here
     */
    function tearDown() {

    }


    /**
     * @test
     */
    public function testlayerOClassOne() {

        $sClassName='layer0\ClassOne';
        $sInterfaceName='layer0\IClassOne';
        $oGotten=$this->oResolver->getInstanceOf('layer0\ClassOne');
        $this->assertInstanceOf($sClassName,$oGotten,"The instance  gotten is not an instances of ".$sClassName);
        $this->assertInstanceOf($sInterfaceName,$oGotten,"The instance  gotten is not  an implementation of  ".$sInterfaceName);

    }


    /**
     * @test
     */
    public function testlayerOClassTwo() {

        $sClassName='layer0\ClassTwo';
        $sInterfaceName='layer0\IClassTwo';
        $oGotten=$this->oResolver->getInstanceOf('layer0\ClassTwo');
        $this->assertInstanceOf($sClassName,$oGotten,"The instance  gotten is not an instances of ".$sClassName);
        $this->assertInstanceOf($sInterfaceName,$oGotten,"The instance  gotten is not  an implementation of  ".$sInterfaceName);

    }


    /**
     * @test
     */
    public function testlayer1ClassOne() {

        $sClassName='layer1\ClassOne';
        $sInterfaceName='layer1\IClassOne';
        $oGotten=$this->oResolver->getInstanceOf('layer1\ClassOne');
        $this->assertInstanceOf($sClassName,$oGotten,"The instance  gotten is not an instances of ".$sClassName);
        $this->assertInstanceOf($sInterfaceName,$oGotten,"The instance  gotten is not  an implementation of  ".$sInterfaceName);

    }


    /**
     * @test
     */
    public function testlayer1ClassTwo() {

        $sClassName='layer1\ClassTwo';
        $sInterfaceName='layer1\IClassTwo';
        $oGotten=$this->oResolver->getInstanceOf('layer1\ClassTwo');
        $this->assertInstanceOf($sClassName,$oGotten,"The instance  gotten is not an instances of ".$sClassName);
        $this->assertInstanceOf($sInterfaceName,$oGotten,"The instance  gotten is not  an implementation of  ".$sInterfaceName);

    }


    /**
     * @test
     */
    public function testlayer2ClassOne() {

        $sClassName='layer2\ClassOne';
        $sInterfaceName='layer2\IClassOne';
        $oGotten=$this->oResolver->getInstanceOf('layer2\ClassOne');
        $this->assertInstanceOf($sClassName,$oGotten,"The instance  gotten is not an instances of ".$sClassName);
        $this->assertInstanceOf($sInterfaceName,$oGotten,"The instance  gotten is not  an implementation of  ".$sInterfaceName);

    }


    /**
     * @test
     */
    public function testlayer2WithMap() {

        $sClassInterface='layer2\IClassOne';
        $oResolver= new reflection\ResolverByReflection($this->getResolverClassByInterfaceName(),$this->getMappingClassInterface());
        $oL2ClassOne=$oResolver->getInstanceOf('layer2\ClassOne');
        $this->assertInstanceOf($sClassInterface,$oL2ClassOne,"The instance  gotten is not an instances of ".$sClassInterface);
        $this->assertEquals($oL2ClassOne->returnTextDependencyLayer1ClassOne(),$this->sTxt,"Error The  returned tet is not the same.");

    }



    /**
     * @test
     */
    public function testlayer2WithStub() {

        $sClassInterface='layer2\IClassOne';
        $sClassOne="Value from stub ClassOne";
        $sClassTwo="Value from stub ClassTwo";
        $oResolver= new reflection\ResolverByReflection($this->getResolverClassByInterfaceName(),$this->getArrayWithStubLayer1($sClassOne,$sClassTwo));
        $oL2ClassOne=$oResolver->getInstanceOf('layer2\ClassOne');
        $this->assertInstanceOf($sClassInterface,$oL2ClassOne,"The instance  gotten is not an instances of ".$sClassInterface);
        $this->assertEquals($oL2ClassOne->returnTextDependencyLayer1ClassOne(),$sClassOne,"The returned text is not  the same, it must be ".$sClassOne);
        $this->assertEquals($oL2ClassOne->returnTextDependencyLayer1ClassTwo(),$sClassTwo,"The returned text is not  the same, it must be ".$sClassTwo);
    }


    private function getArrayWithStubLayer1($sClassOne,$sClassTwo){

        $oMockClassOne=$this->getMockBuilder('layer1\IClassOne')->getMock();
        $oMockClassOne->method("returnTextDependencyLayer0")->will($this->returnValue($sClassOne));

        $oMockClassTwo=$this->getMockBuilder('layer1\IClassTwo')->getMock();
        $oMockClassTwo->method("returnTextDependencyLayer0")->will($this->returnValue($sClassTwo));
        $aMap=array();
        $aMap['layer1\IClassOne']=$oMockClassOne;
        $aMap['layer1\IClassTwo']=$oMockClassTwo;
        return $aMap;

    }




    private function getResolverClassByInterfaceName(){

        return new reflection\resolveInterfaceByName\ResolveInterfaceByNameFirstLetter();

    }

    private function getMappingClassInterface(){

        $aMap=array();
        $oL0ClassOne=&new layer0\ClassOne($this->sTxt);
        $oL0ClassTwo=&new layer0\ClassTwo();
        $aMap['layer0\IClassOne']=&$oL0ClassOne;
        $aMap['layer0\IClassTwo']=&$oL0ClassTwo;
        $oL1ClassOne=&new layer1\ClassOne($oL0ClassOne);
        $aMap['layer1\IClassOne']=&$oL1ClassOne;
        return $aMap;

    }


}