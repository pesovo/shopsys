# Introduction to Backend API

Shopsys Framework backend API is an interface to the application that is dedicated for integration with your third party systems systems (like IS, storage system, ERP, ...).
We use [REST](https://en.wikipedia.org/wiki/Representational_state_transfer) (implemented using [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)) with JSON format and [OAuth2](https://oauth.net/2/) authorization (implemented using [oauth2-bundle](https://github.com/trikoder/oauth2-bundle)).

## Try it

You need to configure the [OAuth](TODO link to our oauth documentation) first.

Get the Bearer token
```bash
curl -X POST \
  'http://127.0.0.1:8000/api/token' \
  -d 'grant_type=client_credentials' \
  -d 'client_id=PASTE_CLIENT_ID_HERE' \
  -d 'client_secret=PASTE_CLIENT_SECRET_HERE'
```

When the credentials are correct, you'll get a similar response
```json
{"token_type":"Bearer","expires_in":3600,"access_token":"eyJ...lKQ"}
```

Don't be surprised, the `access_token` is long.
Use the `access_token` (eg. `eyJ...lKQ`) for the request Bearer authorization

```bash
curl -X GET \
  'http://127.0.0.1:8000/api/v1/products' \
  -H 'Authorization: Bearer eyJ...lKQ'
```

And if you've copied the token correctly, you'll be rewarded by products, eg.

```json
[
    {
        "uuid": "48c846f8-4558-4304-8ccb-83d9cbca8ed8",
        "name": {
            "en": "22\" Sencor SLE 22F46DM4 HELLO KITTY",
            "cs": "22\" Sencor SLE 22F46DM4 HELLO KITTY"
        },
        "hidden": false,
        "sellingDenied": false,
        "sellingFrom": "2000-01-16T00:00:00+0000",
        "catnum": "9177759",
        "ean": "8845781245930",
        "partno": "SLE 22F46DM4",
        "shortDescription": {
            "1": "Television LED ... playback",
            "2": "Sencor SLE 22F46DM4 Hello Kitty ... zaujme!"
        },
        "longDescription": {
            "1": "Television LED, 55 ... B",
            "2": "<p><strong>Sencor SLE ... &nbsp;</p> "
        }
    },
    ...
]
```

If the token is invalid, you'll get `401` HTTP response code.

*Note: For debugging and testing the API, we recommend using [Postman](https://www.getpostman.com/apps) application rather than the bash terminal.*

## Extensibility of the API
If you need to extend your backend API, you can follow the cookbooks:
* [Adding an Attribute to Product Export](/docs/api/adding-an-attribute-to-product-export.md)
* [Creating Custom API Endpoint](/docs/api/creating-custom-api-endpoint.md)

If you need to overwrite an existing core endpoint, you can [create a custom one](/docs/api/creating-custom-api-endpoint.md) and use the same route settings to overwrite the one from the core, 
e.g. provided there is a GET route `products/` in the core, you need to add your own action for the same route:
```php
declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Api\V1;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;

class MyCustomController extends AbstractFOSRestController
{
    /**
     * @Get("/products")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function MyCustomProductsAction(): Response
    {
      // ...
    }
}
```