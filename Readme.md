## TECPAR Challenge.
1. Creating a route that finds a hash, of a certain format, for a certain string given as
   input. 
2. Creating a command that queries the created route and stores the results in the database.
3. Creating a route that returns the results that were recorded.    

## Technologies used.
 - PHP 8
 - MySQl database
 - Symfony Framework 5.4

## Como executar a aplicação.  
Acesar o terminar o utilizar os seguintes comandos:
 ```
 symfony serve  
 php bin/console doctrine:database:create
 php bin/console doctrine:migrations:generate
 php bin/console make:migration
 php bin/console doctrine:migrations:migrate
 ```

## How to use.  
Via command in terminal:
```
php bin/console avato:test string --requests=100
```
or by accessing the route directly:
```
https://localhost:8000/hash/{input_string}/{number_attempts}
```  
The Return of the route will have the following format:
```
[
  {
    "batch": "2022-10-05 08:57:46",
    "block_number": 1,
    "string": "Ávato",
    "key": "wmthVNFA",
    "generated_hash": "0000fc6eb84fd094dc07fffb5f38743d",
    "attempts": 267694
  },
  {
    "batch": "2022-10-05 08:57:47",
    "block_number": 2,
    "string": "0000fc6eb84fd094dc07fffb5f38743d",
    "key": "QRGLvjyY",
    "generated_hash": "00004b917f99f06b3ffa80397a4d9af9",
    "attempts": 47693
  }, 
  { Other results },
  {
    "batch": "2022-10-05 08:57:48",
    "block_number": 10,
    "string": "000087024039bd8c6535ca662f88a388",
    "key": "MObHWTsY",
    "generated_hash": "0000b0bd516851a78e404c20dfc8f1dd",
    "attempts": 26759
  }
  ]
```