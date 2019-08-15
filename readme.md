# Pay.ir Laravel

Laravel package to connect to Pay.ir Payment Gateway

## Installation

`composer require saeedvaziry/payir`

## Publish Configurations

`php artisan vendor:publish --provider="SaeedVaziry\Payir\PayirServiceProvider"`

## Config

Set your api key and redirect url in `.env` file:

    PAYIR_API_KEY=test
    PAYIR_REDIRECT=/payir/callback
    
## Usage

### Payment Controller

    <?php
    
    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use SaeedVaziry\Payir\Exceptions\SendException;
    use SaeedVaziry\Payir\Exceptions\VerifyException;
    use SaeedVaziry\Payir\PayirPG;
    
    class PaymentController extends Controller
    {
        public function pay()
        {
            $payir = new PayirPG();
            $payir->amount = 1000; // Required, Amount
            $payir->factorNumber = 'Factor-Number'; // Optional
            $payir->description = 'Some Description'; // Optional
            $payir->mobile = '0912XXXXXXX'; // Optional, If you want to show user's saved card numbers in gateway
    
            try {
                $payir->send();
    
                return redirect($payir->paymentUrl);
            } catch (SendException $e) {
                throw $e;
            }
        }
    
        public function callback(Request $request)
        {
            $payir = new PayirPG();
            $payir->token = $request->token; // Pay.ir returns this token to your redirect url
    
            try {
                $payir->verify();
    
                return redirect($payir->paymentUrl);
            } catch (VerifyException $e) {
                throw $e;
            }
        }
    }

### Routes

    Route::get('/payir/callback', 'PaymentController@verify');
    
## Usage with facade

Config `aliases` in `config/app.php` :

    'Payir' => SaeedVaziry\Payir\Facades\Payir::class
    
*Send*

    Payir::send($amount, $redirect = null, $factorNumber = null, $mobile = null, $description = null);
    
*Verify*

    Payir::verify($token);
    
## Security

If you discover any security related issues, please create an issue or email me (sa.vaziry@gmail.com)
    
## License

This repo is open-sourced software licensed under the MIT license.
