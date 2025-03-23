# 🛩️Database Class (PHP)

## 👀Overview
This project provides a simple PHP class (`database.php`) for managing database connections and performing basic CRUD operations using `PDO`.

## 🎛️Features
- Establishes a secure connection to a MySQL database using `PDO`
- Supports CRUD operations:
  - Fetching all records from a table
  - Inserting new records
  - Updating existing records
  - Deleting records
- Uses prepared statements to prevent SQL injection
- Implements error handling with `PDOException`

## 🪄Installation
1. Place the `database.php` file in your PHP project directory or Use the defined project..
2. Update the database connection and namespace settings in `database.php`.
3. Ensure you have a MySQL database configured.

## 🔮Usage
### Initialize the Database Connection
```php
require 'database.php';

$db = new database('your_table_name');
```

### Fetch Data
```php
$data = $db->getdata();
print_r($data);
```

### Insert Data
```php
$values = ['column1' => 'value1', 'column2' => 'value2'];
$db->newdata($values);
```

### Update Data
```php
$values = ['column1' => 'new_value'];
$db->editdata('id', 1, $condition);
```

### Delete Data
```php
$db->deletedata('id', 1);
```

## 💡Contributing
Feel free to submit issues and pull requests to improve this project.



