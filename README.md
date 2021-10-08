# jcaillot/laminas-owasp-headers


### OWASP headers Response Listener for the Laminas framework

> Laminas MvcEvent::EVENT_RENDER listener, adds OWASP recommended HTTP headers to the HTTP Response


## Installation

 `composer require jcaillot/laminas-owasp-headers`

In <Your Module>/config/module.config.php add the following declarations:

```php 

    'service_manager' => [
            'invokables' => [
                 ...
                 Listener\OwaspHeadersListener::class => Listener\OwaspHeadersListener::class
            ],
     ],
    'listeners' => [
            ...
            Listener\OwaspHeadersListener::class
    ],
   
    
    'owasp-headers' => [
        # Browsers (or other complying user agents) should only interact with me using secure HTTPS connections:
        # see https://https.cio.gov/hsts/
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
        # Prevents the browser from interpreting files as something else than declared by the content type:
        'X-Content-Type-Option' => 'nosniff',
        'Content-Type' => 'text/html; charset=utf-8',
        # Enables the Cross-site scripting (XSS) filter in the browser:
        'X-XSS-Protection' => '1; mode=block',
        # The browser must not display the transmitted content in frames:
        'X-Frame-Options' => 'DENY',
        # No XML policy file( (for Flash or Acrobat) allowed:
        # see https://www.adobe.com/devnet-docs/acrobatetk/tools/AppSec/xdomain.html
        'X-Permitted-Cross-Domain-Policies' => 'none',
        # Referrer-Policy HTTP header governs which referrer information, sent in the Referer header, should be included:
        'Referrer-Policy' => 'same-origin',
        # Content Security Policy (CSP) requires careful tuning
        # see https://csp-evaluator.withgoogle.com
        # suggested: 'Content-Security-Policy' => 'default-src \'self\'; img-src \'self\'; script-src \'self\'; frame-ancestors \'none\'',
        'Content-Security-Policy' => 'frame-ancestors \'none\'',
        # Selectively enable and disable use of various browser features and APIs
        'Feature-Policy' => 'camera: \'none\'; payment: \'none\'; microphone: \'none\'',

    ],


```

Open your browser console on the network tab and check the headers are added.

## About OWASP recommender headers

More infos on OWASP recommended headers can be found on the OWASP Secure Headers Project Wiki:

[OWASP](https://wiki.owasp.org/index.php/OWASP_Secure_Headers_Project#tab=Headers)

## License

[MIT](https://choosealicense.com/licenses/mit/)
