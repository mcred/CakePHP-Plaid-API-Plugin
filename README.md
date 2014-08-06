CakePHP-Plaid-API-Plugin
=======================

A CakePhp Plugin that interacts with the API from plaid.com. The component will get/post data to/from the API and return an array to the controller.

#Requirements#
*PHP 4,5<br />
*CakePhp 2+<br />
*Plaid API Client ID<br />
*Plaid API Secret Key

#Installation#
```
$ cd /your_app_path/Plugin
$ git submodule add git@github.com:mcred/CakePHP-Plaid-API-Plugin.git Plaid
```

#Configuration#
1. Create an account at https://plaid.com/signup
2. Obtain your API key
3. Create a copy of plaid.php to /app/Config/plaid.php
4. Insert your API Client ID and Secret Key and update any settings
5. Edit your /app/Controller/AppController.php

```
class AppController extends Controller {
	public $components = array('Plaid.Plaid');
}
```

#Reference#
https://plaid.com/docs

#Usage#

UserAdd
```
$this->Plaid->UserAdd($username, $password, $type, $email);
```

UserMFA
```
$this->Plaid->UserMFA($mfa, $access_token);
```

UserUpdate
```
$this->Plaid->UserUpdate($access_token);
```

UserPatch
```
$this->Plaid->UserPatch($username, $password, $access_token);
```

UserDelete
```
$this->Plaid->UserDelete($access_token);
```

Entities
```
$this->Plaid->Entities($id);
```

Institutions
```
$this->Plaid->Institutions();
```

Categories
```
$this->Plaid->Categories();
```

#Change History#
CakePHP Plaid v.1 - 2014-08-06<br />
*Initial Commit
