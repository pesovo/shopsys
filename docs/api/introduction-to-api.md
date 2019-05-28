# Introduction to API

API is a backend interface to the application and should be used for integration with your systems (like IS, storage system, ...).
We use [REST](https://en.wikipedia.org/wiki/Representational_state_transfer) with JSON format and [OAuth2](https://oauth.net/2/) authorization.

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
