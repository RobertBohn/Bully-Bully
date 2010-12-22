<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Admin - Funds</title>
</head>
<body>


<form action="sql.php" method="post">
    <textarea name="query" rows="8" cols="60"><?php echo $_POST[query]; ?></textarea><br />
    <input name="password" type="text" value="<?php echo $_POST[password]; ?>" /><br />
    <input type="submit" value="Submit" /><br />
</form>



<?php
    define("DBHOST","localhost");
    define("DBUSER","themocca_bob");
    define("DBPSW","qwerty");
    define("DBNAME","themocca_sm01");

    $dbh = mysql_connect ( DBHOST, DBUSER, DBPSW ) or die ('I cannot connect to the database because: ' . mysql_error());
    mysql_select_db( DBNAME ); 

    if ( $_POST[password] != "qwerty" ) die ( 'invalid user' );

    if ( substr( $_POST[query], 0, 6 ) == "select" )
    {


mail('robertbohn@hotmail.com', 'the subject', 'the message', null, '-fwebmaster@themocca.com');




        $sql = k($_POST[query]);
        $rows = mysql_query ( $sql ) or die ( 'Could not select from funds because: ' . mysql_error());


        echo '<table border="1" colspacing="1" colpadding = "1"><tr>';

        for ($i=0; $i < mysql_num_fields($rows); $i++) echo '<th>' . mysql_fetch_field($rows, $i)->name . '</th>';
        echo '</tr>';

        while ($cols = mysql_fetch_array($rows))
        {
            echo '<tr>';
            for ($i=0; $i < mysql_num_fields($rows); $i++) echo '<td>' .$cols[$i] . '</td>';
            echo '</tr>';
        }
        echo '</table>';

    }
    else
    {
        $sql = k($_POST[query]);
        mysql_query ( $sql ) or die ('sql error: ' . mysql_error());
        echo 'ok. ' . mysql_affected_rows() .' rows affected';
    }



function k($s)
{
//   return htmlspecialchars(stripcslashes($s));
return stripcslashes($s);
}

?> 

</body>
</html>
