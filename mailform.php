<?php
require("MailSender.php");
$sendn = new Sender();

if(isset($_POST['submit']))
{
    $msg = $sendn->reserve();
}

?>

<html>
<body>
    <form name="myform" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Make your book(s) Reservation</legend>

            <table>
                <tr>
                    <td colspan="2">
                        <p>
                            <?php if(isset($msg))echo $msg;?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>Name:</td><td><input type="text" name="name" id="name"/> </td>
                </tr>
                <tr>
                    <td>Email:</td><td><input type="text" name="email" id="email"/> </td>
                </tr>
                <tr>
                    <td>&nbsp;</td><td><input type="submit" value="Reserve Now" name="submit" id="submit"/> </td>
                </tr>
            </table>

        </fieldset>

    </form>
</body>

</html>
