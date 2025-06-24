<?php

namespace App\Http\Controllers;


class Calculator {

    public $num1;
    public $num2;

//    public function sum($num1, $num2) {
//        $sum = $num1 + $num2;
//        return $sum;
//    }
//
//    public function sub($num1, $num2) {
//        $sum = $num1 + $num2;
//        return $sum;
//    }



    // Constructor to initialize values
    public function __construct($num1, $num2) {
        $this->num1 = $num1;
        $this->num2 = $num2;
    }

    public function sum() {
        return $this->num1 + $this->num2;
    }

    public function sub() {
        return $this->num1 - $this->num2;
    }











}


// in other fie
// we use this

require 'Calculator.php';

$calc = new Calculator();
$sum = $calc->sum(2, 4);
echo "Sum is: " . $sum;

$sub = $calc->sub(10, 5);
echo "\nSubtraction is: " . $sub;


require 'Calculator.php';

$calc = new Calculator(10, 5); // Values set at object creation

echo "Sum is: " . $calc->sum();         // Output: Sum is: 15
echo "\nSubtraction is: " . $calc->sub(); // Output: Subtraction is: 5
