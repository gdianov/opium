# Opium - Simple Yaml DI Container

## How to usage?

### Install: 
> composer require gdianov/opium

1. Create your yaml config like config.yaml:
    ```
     t:
       class: gdianov\opium\tests\classes\T
       constructor:
         - 1
       props:
         - bar: barValue
     
     c:
       class: gdianov\opium\tests\classes\C
       constructor:
         - '@t'
     
     p:
       class: gdianov\opium\tests\classes\P
       constructor:
         - opium
       props:
         - c: '@c'
    ```
    
2. Create Opium instance like this:
    ```
    $configFile = __DIR__ . '/config.yaml';
    $loader = new YamlLoader($configFile);
    $config = $loader->configure();
    $opium = Opium::getInstance(new Container(), $config);
    ```
    
3. Use it.

**Create objects by yaml configuration:**
```php
//$t is instance of: gdianov\opium\tests\classes\T  
$t = $opium->make('t'); 

//$c is instance of: gdianov\opium\tests\classes\C with 
//injected object $t by constructor
$c = $opium->make('c');  

//$p is instance of: gdianov\opium\tests\classes\P with
//injected object $c by property and string by constructor
$p = $opium->make('p'); 

//You can injected dependency by property and constructor.

```
**Also we can create new object dynamically:** 
```php
$t = $opium->makeDynamic([
     'class' => T::class,
     'props' => [
          [
            'bar' => $barValue
          ]
]);
                
 ```  
**Create dynamically and related with yaml config dependency:** 
```php
$c = $opium->makeDynamic([         
    'class' => C::class,
    'constructor' => ['@t']   
]);       

//New C instance with T dependency      
```

**You can get new instance with another params:**
```php
$t = $opium->getWithParams('t', [
        'props' => [
            ['bar' => 'Another Value'],
        ]
]);

//Instance T with new property bar value
             
```

> #### You can combine objects as you like without restricting yourself to anything. Try it.