<?php

namespace App;

class Alert
{
    public static function printMessage($text, $type)
    {
        echo "<div style='text-align:center;margin-bottom:0;' class = 'alert alert-" . $type . "' role = 'alert' >" . $text . "</div>";
    }

    public function showSuccessSignUpAlert()
    {
      
        if (isset($_SESSION['signUpSuccess'])) {
            \App\Alert::printMessage("Sign Up Success", "success");
            unset($_SESSION['signUpSuccess']);
        }
    }
}