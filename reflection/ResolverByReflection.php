<?php


namespace reflection;

/**
 * Class which can return  the instance of a class by using reflection or  analyzing  maps of key-value for classes or interfaces.
 * Interfaces  have an relation the name of the interface and the class this is: IClassName -> ClassName;
 * if   an interface is passed as an argument to  the method getInstanceOf,  it  will remove the first letter of the interface (I)
 * Class ResolverByReflection
 * @package reflection
 */
class ResolverByReflection{

    /**
     * Array with names of classes or instances associated to   instances
     * @var array|null
     */
    private $aMapInstances=null;

    /**
     * Object  which can obtain the name of the class
     * @var IResolveInterfaceByName
     */
    private $oResolverInterface=null;

    /**
     * Constructor of the class
     * @param resolveInterfaceByName\IResolveInterfaceByName $oResolverInterface
     * @param array $aMapInstances
     */
    public function  __construct( resolveInterfaceByName\IResolveInterfaceByName  $oResolverInterface=null, array $aMapInstances=null){

        $this->aMapInstances=$aMapInstances;
        $this->oResolverInterface=$oResolverInterface;


    }

    /**
     * Returns the instance of the class or interfaces passed  by argument
     * @param $sClass  Name of the class or interface  with namespace
     * @return null|object
     */
    public function getInstanceOf($sClass){

        // First of all, it's  checked  if a map was passed and the name of the object matches
        $oMatchMaps=$this->checkMapSingleton($sClass,$this->aMapInstances);
        if($oMatchMaps!=null){
            return $oMatchMaps;
        }else{
            //If the class is not in the map, this starts to analyze  the constructor
            return $this->checkConstructor($sClass,$this->aMapInstances);
        }

    }

    /**
     * Analyzes  the constructor  of the  class or interface  passed in order to see if it has  dependencies
     * @param $sClass
     * @param $aMapInstances
     * @return array|null|object
     */
    private function checkConstructor($sClass,$aMapInstances){

        $reflector = new \ReflectionClass($sClass);
        $constructor = $reflector->getConstructor();
        if($constructor==null){
            return new $sClass;
        }
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters,$aMapInstances);
        if(is_array($dependencies)){
            return $reflector->newInstanceArgs($dependencies);
        }else{
            return $dependencies;
        }

    }

    /**
     * Checks if a map of   names of classes or interfaces  with their values was passed
     * @param $sClassOrInterfaceNameWithNameSpace Name of the class or interface
     * @param $aMapInstances Map  <key,value>
     * @return null
     */
    private  function checkMapSingleton($sClassOrInterfaceNameWithNameSpace,&$aMapInstances){

        if($aMapInstances!=null){
            foreach($aMapInstances as $sNameClass=>$oSingleton){
                if($sNameClass==$sClassOrInterfaceNameWithNameSpace){
                    return $oSingleton;
                }
            }
        }
        return null;

    }

    /**
     * Checks the constructor's values which are  dependencies of the class
     * @param $aParameters
     * @param array $aMapInstances Map  of the instances  with classes or interfaces
     * @return array|null
     */
    private function getDependencies($aParameters,array $aMapInstances=null){

        $aDependencies = array();
        foreach($aParameters as $oParameter){
            $oDependency = $oParameter->getClass();
            if($oDependency==null){
                $aDependencies[] = $this->resolveNonClass($oParameter);
            }else{
                $oInstance=$this->checkMapSingleton($oDependency->name,$aMapInstances);
                if($oInstance!=null){
                    $aDependencies[]=$oInstance;
                }else{
                    $aDependencies[] = $this->getInstanceOf($this->getNameImplementation($this->oResolverInterface,$oDependency->name));
                }
            }
        }
        return $aDependencies;

    }

    /**
     * Checks the  default value of a constructor  when  it has no dependencies
     * @param $oParameter
     * @return mixed
     * @throws Exception
     */
    private function resolveNonClass($oParameter){

        if($oParameter->isDefaultValueAvailable()){
            return $oParameter->getDefaultValue();
        }
        throw new Exception("Cannot resolve the Default value.");
    }

    /**
     * Gets  the name of the class which implements  the interface passed  as an argument
     * @param resolveInterfaceByName $oResolverInterface the class   that resolves or obtains the name of the class
     * @param $sNameOfTheClass
     * @return mixed
     */
    private function getNameImplementation( $oResolverInterface =null, $sNameOfTheClass){

       if($oResolverInterface!=null  ){
           return $oResolverInterface->getClassName($sNameOfTheClass);
       }else{
          return $sNameOfTheClass;
       }

    }

}
