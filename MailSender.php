<?php

class Sender
{
    function  reserve($name, $email, $reservation, $volume, $title, $due)
    {

        $date = strtotime(date("Y-m-d H:i:s"));
        if(empty($name)||empty($email))
        {
            return '<div style="color: #FF0000;font-size: small">Please Provide all Information in the fields! </div>';
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
            return '<div style="color: #FF0000;font-size: small">Please provide a valid email! </div>';
        }
        else {

            try {
                $subject = "Online Reservation System " ;
                $message = '
					<html>
						<head>
						<title>Online Reservation System </title>
						</head>

						<body style="font-family:verdana, arial; font-size: .11em;">
						You\'re receiving this email because you reserved a book.<br/><br/>

						Your information are as follows:<br/>

						Name: ' . $name . '<br/>
						Reservation Number: ' . $reservation . '<br/>
						Title : ' . $title . '<br/>
						Book code: ' . $volume . '<br/>
						Due date: ' . $due . '<br/><br/><br/>
						
						Thank you!<br/><br/>

						</body>
					</html>';


                $headers = 'From: onlinelibrarycct@gmail.com ' . "\r\n" .
					'Cc: onlinelibrarycct@gmail.com ' . "\r\n" . 
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html' . "\r\n";

                $retval = mail($email, $subject, $message, $headers);
                if ($retval == true) {
                    //return "Message could not be sent...";
                    $response = "success";
                } else {
					echo error_get_last()["message"];
					$response = "failure";
                }

                //maybe save to our db here


                if ($response == "success") {
                    return '<strong><div style="font-size: small;color: #3300FF">Successfully booked on: ' . date('Y-m-d H:i:s') . ' </div></strong><div style="font-size: medium;color: #006600"> and email sent.</div>';
                //isset($response)
                } elseif ($response == "failure") {
                    return '<strong><div style="font-size: small;color: #3300FF">Successfully booked on: ' . date('Y-m-d H:i:s') . ' </div></strong><div style="font-size: medium;color: #006600"> but <div  style="font-size: medium;color:red" > email NOT Sent.</div></div>';
                }
            }//try
            catch (Exception $exc) {
                echo($exc->getMessage() . "<br>");
            }

        }//end if

    }//end reserve



}//end class MailSender
