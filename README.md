# wp-kit/cleanoquent

This is a wp-kit component that serves clean [```Eloquent```](https://laravel.com/docs/5.4/eloquent) models for Wordpress.

## Installation

If you're using [```Themosis```](http://framework.themosis.com/), install via [```Composer```](https://getcomposer.org/) in the root of your ```Themosis``` installation, otherwise install in your ```Composer``` driven theme folder:

```php
composer require "wp-kit/cleanoquent"
```

## Usage

### Model

```wp-kit/cleanoquent``` comes with various models which you can extend. 

```php
namespace Theme\Models;

use WPKit\Cleanoquent\Model\Post;

class Address extends Post {
	
    /**
	     * The post_type associated with the model.
	     *
	     * @var string
	     */
		protected $post_type = 'wc-address';
		
		/**
	     * The magic_meta attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $magic_meta = [
			'_first_name' => 'first_name',
			'_last_name' => 'last_name',
			'_company' => 'company',
			'_address_1' => 'address_1',
			'_address_2' => 'address_2',
			'_city' => 'city',
			'_state' => 'state',
			'_postcode' => 'postcode',
			'_country' => 'country',
			'_email' => 'email',
			'_phone' => 'phone'
		];
    
}
```

## Get Involved

To learn more about how to use ```wp-kit``` check out the docs:

[View the Docs](https://github.com/wp-kit/theme/tree/docs/README.md)

Any help is appreciated. The project is open-source and we encourage you to participate. You can contribute to the project in multiple ways by:

- Reporting a bug issue
- Suggesting features
- Sending a pull request with code fix or feature
- Following the project on [GitHub](https://github.com/wp-kit)
- Sharing the project around your community

For details about contributing to the framework, please check the [contribution guide](https://github.com/wp-kit/theme/tree/docs/Contributing.md).

## Requirements

Wordpress 4+

PHP 5.6+

## License

wp-kit/cleanoquent is open-sourced software licensed under the MIT License.
