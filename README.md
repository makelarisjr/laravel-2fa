# Laravel 2FA Package

This package provides all the necessary tools for a complete implementation of Google's OTP and Yubikey 2FA.

## Requirements
+ **PHP:** >=7.4
+ **Laravel:** >=7.0

## Installation

Type the following command in your terminal/command line:

```composer require makerarisjr/laravel2fa```

The service provider will automatically get registered. You may also manually add the service provider in your config/app.php file:

```php
'providers' => [
    // ...
    MakelarisJR\Laravel2FA\Laravel2FAServiceProvider::class,
];
```

Publish the configuration file by typing the following command:

```
php artisan vendor:publish --provider="MakelarisJR\Laravel2FA\Laravel2FAServiceProvider"
```

Next, you have to add the necessary trait to the authenticatable model. This package can be used by any model, and you are not limited only to the User model.

```php
use MakelarisJR\Laravel2FA\Traits\Has2FA;
```

Now that you have set up the model, create a Route group with all the routes that will require the user to have a valid OTP authorization.

Example:
```php
Route::group(['middleware' => ['auth', 'otp']], function(){
    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');
    Route::view('/devices', 'devices')
        ->name('device.list');
});
```
You can add the `otp` middleware alias to any group you like. Furthermore, the middleware will automatically prompt the user to enter the
OTP code if the session needs to be refreshed. You don't need to implement your own verification logic if you use the middleware.

Last but not least, run the migrations.

```
php artisan migrate
```

This will create the following tables:
+ otp_devices
+ otp_backup_codes
+ otp_remember_tokens

## Usage

### Adding Devices
Now it's time to add a new device to our model. The trait that we added provides a `addDevice` method which accepts the following parameters:

```php
function addDevice(string $name, string $otp_secret, string $type = OtpDevice::TYPE_GOOGLE): OtpDevice
```

For type, you may choose one of the following:
+ OtpDevice::TYPE_GOOGLE
+ OtpDevice::TYPE_YUBIKEY

In case of Yubikey, you may provide the full key which is 44 characters, or the device id which is the first 12 characters.
The package will "cut" the string accordingly. 

For Google OTP you will need to generate a QR code before or enter the secret manually to your device.
Here is a simple example:

```php
$user = User::find(1);

// ['secret' => string, 'qrcode' => string]
$data = $user->generateGoogleQRCode('My Application', $user->email);
$user->addDevice('My iPhone', $data['secret']);
```

The QRCode is in the form of a base64 string which can be returned to the user and be scanned by the phone.

### Verify OTP

As mentioned before, the `otp` middleware will handle the verification without having to do something yourself. It is possible however to create your very own 
implementation, especially if you are planning to use another frontend framework like `Vue`, `React`, etc...

To verify the code inserted by the user, use the following method provided by the trait:

```php
$user->verifyOtp(string $otp): bool
```

The `string $otp` can either be the code provided by the Google authenticator, or the 44 digits code provided by the Yubikey, or one of the backup codes.
The method will return `true` if the verification is successful or `false` if it's not.

### Backup Codes

It is also possible to generate backup codes that can be used in case you lose your device. This is a recommended safety practise because in the authentication device is lost or destroyed you won't be able to sign in to your account.

To generate the codes, you may use the following method:
```php
$user->generateBackupCodes(int $total = 8, bool $force = false): array
```

The first numeric parameter designates the total number of codes that will be generated. The default is 8.
The second parameter, force, designates whether new codes will be generated. Once the backup codes are created, it is not possible to create them again, unless you set `force` to true which in that case, the old ones will be deleted
and a new batch will be created.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel2FA, please send an e-mail to makelarisjr via [makelarisjr@hackthebox.eu](mailto:makelarisjr@hackthebox.eu). All security vulnerabilities will be promptly addressed.

## License

The Laravel2FA is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).