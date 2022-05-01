# Cache php component
This is easy-to-use php component to add caching in your project. See `index.php` for examples.
### Public methods:
- `getInstance()` - inits the component
- `set()` - sets a cache for the key for some period
- `get()` - returns cached data by the key
- `delete()` - deletes a cache file for the key 
## How to use
### 1. Include Cache class and init it. It uses `cache` folder by default, but you can change this. 
```php
require_once 'Cache/Cache.php';

$cache = Cache::getInstance('./runtime/cache');
```
### 2. Use `get()` and `set()` methods with keys you need.
```php
$reviews = $cache->get('reviews');

if (!$reviews) {
  $reviews = (new Review())->getAll();
  $cache->set('reviews', $reviews);
}
```
or
```php
if (!$info = $cache->get('server_info')) {
  $cache->set('server_info', $_SERVER);
}
```
### 3. Do something with data you've got.
```php
foreach($reviews as $review) {
  echo "<div>{$review['name']}<br>{$review['text']}</div>";
}
```
or
```php
echo '<pre>' . print_r($info, true) . '</pre>';
```
