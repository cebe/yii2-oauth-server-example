# yii2-oauth-server-example

Example application to demonstrate the implementation of an OAuth-Server with Yii 2

### Getting started

- $ `git clone https://github.com/cebe/yii2-oauth-server-example.git`
- $ `cd yii2-oauth-server-example`
- $ `composer install`
- create new file `config/db.php`
- put content in that file as:
  
  ```php
  <?php

  return [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=localhost;dbname=oauth-jwt-server',
      'username' => 'PLACEHOLDER', // replace this
      'password' => 'PLACEHOLDER', // replace this
      'charset' => 'utf8',

      // Schema cache options (for production environment)
      //'enableSchemaCache' => true,
      //'schemaCacheDuration' => 60,
      //'schemaCache' => 'cache',
  ];
  ```

 - adjust DB name, user and password accordingly in above file & make sure you have created above database externally (for e.g. phpmyadmin)
 - hit migration by $ `./yii migrate`
 - generate private key $ `openssl genrsa -out private.key 2048`
 - generate public key $ `openssl rsa -in private.key -pubout > public.key`
 - above both commands will generate 2 files (`public.key` & `private.key`) in project root dir
 - give them necessary permission $ `sudo chmod -R 600 public.key private.key` (make sure you are present ($ - present working directory) in project root dir (e.g. yii2-oauth-server-example) )
 - start server by $ ` php -S localhost:7876`
 - Note: if you choose different port make corresponding change in file `components/OauthServerClient.php` in client app (in your local project)
 - visit http://localhost:7876/web/index.php in browser
 - now you should have this web app running in browser


### Next step


 - perform SignUp
 - you will be needed to verify email which can be done as:
 - new file in runtime dir is created when you SignUp. e.g. file path with name  `yii2-oauth-server-example/runtime/mail/20191225-075658-6512-0994.eml`
 - file name in your case will be bit different
 - grab verification link as
 - Find `Ctrl` + `F` text `href` and grab that link
 - That link will be like http://localhost:7876/web/index.php?r=3Duser%2Fregistration%2Fconfirm&a=
mp;id=3D6&amp;code=3DsafPsl8rjo5q0GJGZW_2Iw1qmMGC_3ax
 - polish it by removing `3D`, `amp;`, newline char and now that link looks like http://localhost:7876/web/index.php?r=user%2Fregistration%2Fconfirm&id=1&code=safPsl8rjo5q0GJGZW_2Iw1qmMGC_3ax
 - copy the new polished link and paste in browser's new tab and hit enter.
 - Your account will be verified
 - create new client at http://localhost:7876/web/index.php?r=oauth%2Fclient%2Findex
 - redirect_uri can be kept as (if your client app runs on port 7878) http://localhost:7878/web/index.php?r=user%2Fsecurity%2Fauth&authclient=oauthserver
 - save the new client
 - That's done from the server side. Now lets move to client app https://github.com/cebe/yii2-oauth-client-example

### Notes

 - this example only implements [Authorization code grant](https://oauth2.thephpleague.com/authorization-server/auth-code-grant/)
 - this app is for only server rendered app (a typical PHP MVC app) and not for SPA (Single page application)
