## Request-Goblin

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-webhook-client/run-tests?label=tests)

Request-Goblin is a multiple request handling Library. The way the two apps communicate is with a simple HTTP request.

This Algorithm allows you to receive Multiple Request in a Laravel app. It has support for [verifying signed calls](#verifying-the-signature-of-incoming-webhooks), [storing payloads and processing the payloads](#storing-and-processing-webhooks) in a queued job.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-webhook-client.jpg?t=1" width="419px" />](https://techuplabs.com/)

We invest a lot of resources into creating [best in class open source packages](https://techuplabs.com/). You can support us by [buying one of our paid products](https://techuplabs.com/).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://techuplabs.com/partner-us.php). We publish all received postcards on [our virtual postcard wall](https://techuplabs.com/).

## Installation

You can install the package via composer:

```bash
git clone https://github.com/TechUp-Labs/Request-Goblin.git
```

### Install the repository

You can publish the config file with:

```bash
php artisan migrate:refresh --seed
```

This is the contents of the file that will be published at `app\Http\Controllers\API\GoblinController.php`:

```php
public function show($request)
{
    $hash_code = $request->header('X-Goblin-Token');
    //return $hash_code;
    if(!isset($hash_code)){ 
        return["status"=>"error","message"=>"Please Pass Valid Goblin token"]; 
    }
    $goblin_check = Goblin::select("hash_code","status","message")->where("user_id","=",Auth::user()->id)->where("hash_code","=",$hash_code)->first();
    if(!isset($goblin_check->hash_code)){ 
        return["status"=>"error","message"=>"Please Pass Valid Goblin token"]; 
    }
    if(isset($goblin_check->status)){ 
        $goblin_check->message = json_decode($goblin_check->message);
        return $goblin_check; 
    }else{ 
        return 0; 
    }
}
```

In the `X-Goblin-Token` key of the request, you should add a valid generated `X-Goblin-Token`. This value should be provided by the app that will send you `X-Goblin-Token`.

The frond will store the tokens in stack and respond to the backend while making the request. Processing the `X-Goblin-Token` of the request is done via a above function.  It's recommended to pass the `X-Goblin-section-id` to create the proper log of what the users are seeing. If the connection breaks before the response recived to the frontend the response will be stored in the log. If response is not recived from the backend then the front-end will try to re-send the request which will return the old log and then drop that `X-Goblin-Token`. 

### Preparing the Collection

By default, all `X-Goblin-Token` calls is under Employess Folder Only.

We had limited this with module to demonstrate that it is completly optional use where ever you got very high trafic:
```bash
wget https://ns1.techsingularity.com/server45/RequestGoblin/RequestGoblin.postman_collection.json
```

After the `{{base_url}}` has to be changed as per you host:


### Easy two step implementation

Finally, let's take care of the routing. At the app that sends webhooks, you probably configure an URL where you want your webhook requests to be sent. Here's an example:

```php
$goblin = (new GoblinController)->show($request);
if($goblin){ return $goblin; }
```

Response Part, this will register a request to the database by this package. Because the app that sends webhooks to you has no way of getting a token :

```php
$status = "success";
$message = $response;
return (new GoblinController)->store($request, $message, $status);
```

## Usage

In the `X-Goblin-Token` key of the request, you should add a valid generated `X-Goblin-Token`. This value should be provided by the app that will send you `X-Goblin-Token`.

The frond will store the tokens in stack and respond to the backend while making the request. Processing the `X-Goblin-Token` of the request is done via a above function.  It's recommended to pass the `X-Goblin-section-id` to create the proper log of what the users are seeing. If the connection breaks before the response recived to the frontend the response will be stored in the log. If response is not recived from the backend then the front-end will try to re-send the request which will return the old log and then drop that `X-Goblin-Token`. 

### Verifying the signature of incoming Request

```php
public function store($request, $message, $status)
{
    $hash_code = $request->header('X-Goblin-Token');
    $section_id = $request->header('X-Goblin-section-id');
    $goblin = Goblin::where('hash_code', '=', $hash_code)->first();
    if(isset($goblin->hash_code)){
        $goblin->section_id = $section_id;
        $goblin->message = json_encode($message);
        $goblin->status = $status;
        $goblin->user_id = Auth::user()->id;
        $goblin->save();
        unset($goblin->id);
        unset($goblin->section_id);
        unset($goblin->user_id);
        unset($goblin->created_at);
        unset($goblin->updated_at);
        $goblin->message = $message;
        return $goblin;            
    }else{
        return["status"=>"error", "message"=>"Invalid Goblin Token"];
    }
}
```
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email munshiji.zakir@gmail.com instead of using the issue tracker.

## Postcardware

You're free to use this algorithm, but if it makes it to your production environment, we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: -

We publish all received postcards [on our company website](https://techuplabs.com).

## Credits

- [Zakir S.](https://techsingularity.com/cv/)
- [All Contributors](../../contributors)

## Testimonials

If you want to share with us your thoughts on Request Goblin or showcase what you have built with it (it could be any aspect of our product, not only the React library), you can do it here: <a href="https://www.feedspace.io/u/zn9fdo8" target="_blank">Request Goblin Testimonials Form</a>

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
