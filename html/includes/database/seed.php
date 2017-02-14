<?php
require_once('../../vendor/fzaninotto/faker/src/autoload.php');
require_once('../index.php');
//if(!$session->is_admin_logged_in()){ redirect_to("../../public/login.php"); }

$faker = Faker\Factory::create();

for ($i = 0; $i < 10; $i++) {
    $user = new User($faker->username, 'pass', $faker->firstName, $faker->lastName, $faker->email);
    $result = $user->save();
    echo ($result)? "<br/>#{$i} user was created<br/>": "<br/>#{$i} user creation failed<br/>";
}

$admin = new Admin("superadmin", "pass", "super", "admin", "super@admin.com");