<?php
require_once('../../vendor/fzaninotto/faker/src/autoload.php');
require_once('database.php');

$faker = Faker\Factory::create();
$sql = "INSERT INTO `users` (username, password, first_name, last_name, email) VALUES ";
for ($i = 0; $i < 10; $i++) {
    $sql = $sql . "('{$faker->username}', 'pass', '{$faker->firstName}', '{$faker->lastName}', '{$faker->email}'),";
}
$sql = rtrim($sql, ',') . ";";
echo $sql;
$result = $database->query($sql);