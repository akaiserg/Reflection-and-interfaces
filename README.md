# Reflection  and Interfaces in PHP

Basic useful feature list:

 In order to understand how  a Dependency Injection Container works, you have to understand how   PHP  does reflection. 
 
 The way to  use reflection  in PHP, it's  by calling the class <b>ReflectionClass</b>.


```PHP
$reflector = new \ReflectionClass($sClass);
```

Once you get the class you  can get the information of the   constructor by calling the method <b>getConstructor()</b>

```PHP
$constructor = $reflector->getConstructor();
```

If the  return is not null you can get the arguments of the constructor as well.


```PHP
$parameters = $constructor->getParameters();
```

Finally when  you get the parameters inside of  an array  you can reflect  on each parameter  and you can apply  the same process again and again  to  get  all the dependencies  to generate   each class.

The problem  is that  you can  reflect on an interface  and   it is  well known  everyone should  use  interfaces instead of  a specific  class  as a dependency, therefore,  many DIC use  this concept of   key-value pair where   the key normally is the interface and the value is the name of the specific class which  implements  this interface.
This is useful  in some cases  but if you have  a kind of  standard  for naming  your classes and  interfaces,  you can use it to resolve the class which implements the interfaces  that is  defined as a dependency.

For instance:

```PHP
/**
     * Return the name of the class with its namespace
     * @param $sClassNameOrInterface
     * @return string
     */
    public function getClassName($sClassNameOrInterface){

        if (class_exists($sClassNameOrInterface)) {
            return $sClassNameOrInterface;
        }else{
            // separates   by \
            $aNameSpaceAndInterfaceName=explode("\\",$sClassNameOrInterface);
            $iCount= sizeof($aNameSpaceAndInterfaceName);
            $sInterfaceNameWithoutNameSpace=$aNameSpaceAndInterfaceName[$iCount-1];
            $sNameOfTheClass = substr($sInterfaceNameWithoutNameSpace, 1);
            $aNameSpaceAndInterfaceName[$iCount-1]=$sNameOfTheClass;
            return implode("\\", $aNameSpaceAndInterfaceName);
        }

    }
```
In this example,  each interface has an I as the first letter of   its name  and the   class which implements this interface doesn't have this I.


Class that we  want  its instance.

```PHP
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

```
Getting the instance.

```PHP
$oResolver= new reflection\ResolverByReflection(new reflection\resolveInterfaceByName\ResolveInterfaceByNameFirstLetter());

$oClassThree=$oResolver->getInstanceOf('layer1\ClassOne');
echo $oClassThree->returnTextDependencyLayer0();
```

