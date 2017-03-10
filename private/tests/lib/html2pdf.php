<?php
/**
 * Created by PhpStorm.
 * User: valentinlacour
 * Date: 16/11/16
 * Time: 21:40
 */


include '../../../private/lib/html2pdf.php';

ob_start();
?>
<style type="text/css">
    <!--
    table { vertical-align: top; }
    tr    { vertical-align: top; }
    td    { vertical-align: top; }
    .big{
        font-size: 30px;
        font-weight: bold;
    }
    .upcase{
        text-transform: uppercase;
    }
    .little{
        font-size: 15px;
        font-weight: 100;
    }
    .text-right{
        text-align: right;
    }
    .text-center{
        text-align: center;
    }
    .vMiddle{
        vertical-align: middle;
    }
    .mark{
        font-size: 25px;
    }
    -->
</style>
<page backcolor="#FEFEFE" backimgx="center" backimgy="bottom" backimgw="100%" backtop="10mm" backbottom="30mm" backleft="10mm" footer="date;heure;page" style="font-size: 12pt">
    <bookmark title="Lettre" level="0" ></bookmark>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
        <tr>
            <td rowspan="6" style="width:50%; ">
                <qrcode value="A2HGHHSIA3XC" c="L" style="border: none; width: 40mm;"></qrcode>
            </td>
        </tr>
    </table>
</page>
<?
$content = ob_get_clean();
$html2pdf = new HTML2PDF('P', 'A4', 'fr');
$html2pdf->pdf->SetDisplayMode('fullpage');
$html2pdf->writeHTML($content);
$datas = $html2pdf->Output('test.pdf');