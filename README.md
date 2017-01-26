[![Build Status](https://circleci.com/gh/Elma/DevExpressBundle.png?style=shield&circle-token=68c368dfa20dfcf807557136ea6a555da88c3adf)](https://circleci.com/gh/Elma/DevExpressBundle/tree/master)
[![Coding Style]( https://styleci.io/repos/77622700/shield)](https://styleci.io/repos/77622700)

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

Here is a detailed example of an implementation.
```php
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(...)
     */
    public function indexAction(Request $request)
    {
        // Initiate the parser
        $parser = new SearchQueryParser(); 
        // Parse the DevExpress object
        $query = $parser->parse(json_decode($request->get('loadOptions')));

        // Link between the column header and the doctrine field
        $map = [
            "Username" => "u.username",
            "FirstName" => "u.firstName"
        ];
        // Create the config with the mapping
        $config = new DoctrineQueryConfig($map);

        // Return the data and the total number of item
        return $this->json([
            'content' => $this->getContent($config, $query),
            'total' => $this->getTotal($config, $query)
        ]);
    }
```
Return the data
```php
    private function getContent(DoctrineQueryConfig $config, SearchQuery $query)
    {
        // Create the query builder
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        // Select Data from the DB
        $queryBuilder->select('u')->from('AcmeUserBundle:Order', 'u');

        // Create the query handle
        $handler = new DoctrineQueryHandler($config, $queryBuilder, $query);
        // Binds the filters, pagination and sorting
        $queryBuilder = $handler->addAllModifiers();

        return $queryBuilder->getQuery()->getResult();
    }
```
Return the number of items
```php
    private function getTotal(DoctrineQueryConfig $config, SearchQuery $query)
    {
        $queryBuilder = $this->getDoctrine()->getManager('catalogue')->createQueryBuilder();
        $queryBuilder->select('COUNT(u)')->from('AcmeUserBundle:Order', 'u');

        $handler = new DoctrineQueryHandler($config, $queryBuilder, $query);
        // Add only the filters. You must not add the pagination. You should not add sorting (useless for counting)
        $handler->addFilters();

       return $queryBuilder->getQuery()->getSingleScalarResult();
    }
```
