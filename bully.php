<?php session_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><?php 
include ( 'bully.inc' ); 

$tabs = $_GET[t];
$menu = $_GET[m];
$detail = $_GET[d];
$pagenbr = $_GET[p];
$signedin = $_SESSION['s'];


if ($tabs == '') $tabs = '0';
if ($pagenbr == '') $pagenbr = '1';
if ($signedin == '') $signedin = 'n';

if ($tabs == '0')
{
    switch ($menu)
    {
       case '1':   // Funds
           translateXml ( PATH . 'xml/funds.php?t=0&m=1&s='. $signedin . '&', 'xml/funds.xsl' );
           break;

       case '2':   // Process
           translateXml ( PATH . 'xml/process.php?t=0&m=2&s='. $signedin . '&', 'xml/process.xsl' );
           break;

       case '3':   // Stocks
           translateXml ( PATH . 'xml/stocks.php?t=0&m=3&p=' . $pagenbr . '&s='. $signedin . '&', 'xml/stocks.xsl' );
           break;

       case '4':   // Signals
           translateXml ( PATH . 'xml/adminsignals.php?t=0&m=4&s='. $signedin . '&', 'xml/adminsignals.xsl' );
           break;

       case '5':   // Signin
           if (isset($_POST[username]))
           {
               if ($_POST[username] == 'robertbohn' && $_POST[password] == 'qwerty')
               {
                   $_SESSION['s'] = 'y';
                   translateXml ( PATH . 'xml/process.php?t=0&m=2&s=y&', 'xml/process.xsl' );
               }
               else
               {
                   translateXml ( PATH . 'xml/signin.php?t=0&m=5&s=n&', 'xml/signine.xsl' );
               }
           }
           else
           {
               translateXml ( PATH . 'xml/signin.php?t=0&m=5&s=n&', 'xml/signin.xsl' );
           }
           break;

       case '6':   // Signout
           $_SESSION['s'] = 'n';
           translateXml ( PATH . 'xml/signedout.php', 'xml/signedout.xsl' );
           break;

       case '7':   // Settings
           translateXml ( PATH . 'xml/settings.php?t=0&m=7&s='. $signedin . '&', 'xml/settings.xsl' );
           break;

       case '8':   // Backup
           translateXml ( PATH . 'xml/backup.php?t=0&m=8&s='. $signedin . '&', 'xml/backup.xsl' );
           break;

       case '9':   // History
           translateXml ( PATH . 'xml/adminhistory.php?t=0&m=9&s='. $signedin . '&', 'xml/history.xsl' );
           break;

       default:
          translateXml ( PATH . 'xml/funds.php?t=0&m=1&s='. $signedin . '&', 'xml/funds.xsl' );
          break;
    }
}
else
{
    switch ($menu)
    {
       case '10':   // Balance
           translateXml ( PATH . 'xml/balance.php?t=' .$tabs . '&m=10&s='. $signedin . '&', 'xml/balance.xsl' );
           break;

       case '11':   // Positions
           translateXml ( PATH . 'xml/positions.php?t=' .$tabs . '&m=11&s='. $signedin . '&', 'xml/positions.xsl' );
           break;

       case '12':   // Trades
           translateXml ( PATH . 'xml/trades.php?t=' .$tabs . '&m=12&p=' . $pagenbr . '&s='. $signedin . '&', 'xml/trades.xsl' );
           break;

       case '13':   // Deposits
           translateXml ( PATH . 'xml/deposits.php?t=' .$tabs . '&m=13&s='. $signedin . '&', 'xml/deposits.xsl' );
           break;

       case '14':   // Signals
          translateXml ( PATH . 'xml/signals.php?t=' .$tabs . '&m=14&s='. $signedin . '&', 'xml/signals.xsl' );
           break;

       case '15':   // Stocks
           translateXml ( PATH . 'xml/fundstocks.php?t=' .$tabs . '&m=15&p=' . $pagenbr . '&s='. $signedin . '&', 'xml/fundstocks.xsl' );
           break;

       case '16':   // History
           translateXml ( PATH . 'xml/history.php?t=' .$tabs . '&m=16&s='. $signedin . '&', 'xml/history.xsl' );
           break;

       case '17':   // Settings
           translateXml ( PATH . 'xml/fundsettings.php?t=' .$tabs . '&m=17&s='. $signedin . '&', 'xml/fundsettings.xsl' );
           break;

       case '20':   // View Stock
           translateXml ( PATH . 'xml/viewstock.php?t=' .$tabs . '&m=20&d=' . $detail .'&s='. $signedin . '&', 'xml/viewstock.xsl' );
           break;

       case '21':   // View Position
           translateXml ( PATH . 'xml/viewposition.php?t=' .$tabs . '&m=21&d=' . $detail .'&s='. $signedin . '&', 'xml/viewposition.xsl' );
           break;

       case '22':   // View Trade
           translateXml ( PATH . 'xml/viewtrade.php?t=' .$tabs . '&m=22&d=' . $detail .'&s='. $signedin . '&', 'xml/viewtrade.xsl' );
           break;

       case '23':   // View Signal
           translateXml ( PATH . 'xml/viewsignal.php?t=' .$tabs . '&m=23&d=' . $detail .'&s='. $signedin . '&', 'xml/viewsignal.xsl' );
           break;

       default:
           translateXml ( PATH . 'xml/balance.php?t=' .$tabs . '&m=10&s='. $signedin . '&', 'xml/balance.xsl' );
           break;
    }
}

?>