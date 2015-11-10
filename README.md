
# Adding CSS LESS For Phile CMS

This allows the use of less file(s) to be compiled into css files.
This Plugin  uses [leafo/lessphp](https://packagist.org/packages/leafo/lessphp) as a dependent.

After Site Development is completed, it is advised to turn off plugin.



## Installation
### Composer
```
php composer.phar require sturple/phile-less:dev-master
```

### Download
```
* Install [Phile](https://github.com/PhileCMS/Phile)
* Clone this https://github.com/sturple/phileLess repo into `plugins/sturple/phileLess/
```

## Usage

### setup config.php

``` php
$config['plugins']['sturple\\phileLess'] =
array(
	'active'			=>false,
	'formatter' 		=> 'lessjs', 
	'comments'			=> false,
	'inputFile' 		=> 'css/style.less',
	'outputFile' 		=> 'css/style.css',		
);

```

#### Options

| Option | Values | Description |
| ------ | ------- | ----------- |
| formatter | {**lessjs** | compressed | classic} | See [documentation](http://leafo.net/lessphp/docs/#php_interface)  |
| comments | {true|**false**} Leave Comments  |
| inputFile | Path and File| Relative Path to the **Current Theme** |
| outputFile| Path and File| Relative Path to the **Current Theme** |

## Runtime Errors
```
* there is no input file.
* there is an error in your css code.
* the output directory is not writable.
```

