[![Build Status](https://circleci.com/gh/Elma/DevExpressBundle.png?style=shield&circle-token=68c368dfa20dfcf807557136ea6a555da88c3adf)](https://circleci.com/gh/Elma/DevExpressBundle/tree/master)

## Overview ##

- This bundle is an not an official bundle of the DevExpress team.
- This bundle does not include any files related to the DevExpress libraries
- This bundle provides a simple (and incomplete) bridge between the DevExpressJs widgets and doctrine
- This bundle is a WIP and PR are more than welcome

Currently only a dxDataGrid bridge is provided

## Installation ##
Add the bundle to your composer.json file:
```
composer require elma/devexpressbundle "~1.0@dev"
```
Register the bundle in your composer
```
//File : app/AppKernel.php
$bundles = [
    // ...
    new Bilendi\DevExpressBundle\BilendiDevExpressBundle,
    // ...
]
```
## dxDataGrid ##

First you have to create a queryBuilder and select domething from an entity. It's up to you to build your query with parameters that are defined in your code and note in the loadOptions.

This bridge only transform the loadOptions data to exploitable DQL pieces. 

```php
// Create a queryBuilder and use it
$querybuilder = $this->em->createQueryBuilder();
$queryBuilder->select('')->from('')->...;

// Then create an instance of SearchQueryParser or use the service
$parser = new SearchQueryParser();

// Call the parse function on the decoded json data of the loaOptions provided by the dataGrid custom service load function
$data = json_decode($request->get("loadOptions"));
$query = $parser->parse($data);

// Create a config in which you can define default filters and columns identifier to entity field mapping
$map = [
    'itemId' => 'alias.itemId'
];
$config = new DoctrineQueryConfig($map);

// Create a query handler and add the filters/sorting/pagination
$handler = new DoctrineQueryHandler($config, $queryBuilder, $query);
$handler->addAllModifiers(); // Add all modifiers at once

// And then you can get the results, for example
$querybuilder->getQuery->getResults();
```




