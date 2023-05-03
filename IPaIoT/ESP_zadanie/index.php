<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

    <style>
        .img_1 {
            width: 50%;
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .values{
        	font-family: Open Sans, Helvetica, Arial, sans-serif;
            width: 50%;
            margin: auto;
            border: 3px solid #F79F39;
            border-radius: 10px;
            border-style: groove;
            font-size: 18px;
        }
        h1{
        	font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
            text-align: center;
            font-size: 35px;
            color: #465660;
        }
        h3{
            text-align: right;
            color: #8C989E;
            padding-right: 5px;
            font-size: 16px;
        }
        b{
        	color: #465660;
        }
        p{
        	color: #465660;
            padding-left: 5px;
        }
    </style>
</head>
<body>
<?php
    // https://www.auris-audio.cz/files/static_pages_files/images/zdroje%20hluku%20%20-%20hladiny%20hluku%20v%20dB(1).jpg
    $door_stat = "closed";  // pre dvere
    $sound_z = 1925;  // hodnota pre zelené svetlo (v pohode hlučnosť)
    $sound_o = 1955;  // hodnota pre žlté svetlo (hlasitosť zvyčená treba dávať pozroe)


    $temp_max = 25;  // teplota ako nad zapne sa ventilátor

    echo '<h1>Factory Dashboard </h1> ';
    
    // čitanie z esp
    $sound = $_GET["snd"];
    $temp = $_GET["tmp"];
    $door = $_GET["door"];
    $time = date('H:i:s');
    

    //zápis do súboru z esp hodnôt
    if($sound != 0 && $temp != 0){
    $file_data = "| $time | $sound | $temp | $door |".PHP_EOL;
    $file_data .= file_get_contents('sensors.txt');
    file_put_contents('sensors.txt', $file_data);
    }

    // čitanie pre obrázok hodnôt
    $file2 = fopen("sensors.txt","r") or die("Unable to open file!");
    $text2 = fread($file2,filesize("sensors.txt"));
    $text_line = explode("|",$text2);


    $sound_txt = (int)$text_line[2];
    $temp_txt = (int)$text_line[3];
    $door_txt = (int)$text_line[4];
    if($door_txt == 1){
        $door_stat = "open";
    }
    else{
        $door_stat = "closed";
    }
    

    if($sound_txt < $sound_z){
        if($temp_txt > $temp_max && $door_txt == 1){
            echo "<img src='z_svetlo/1_1_1.png' class = 'img_1' >";
        }
        if($temp_txt > $temp_max && $door_txt == 0){
            echo "<img src='z_svetlo/1_0_1.png' class = 'img_1' >";
        }
        if($temp_txt < $temp_max && $door_txt == 0){
            echo "<img src='z_svetlo/1_0_0.png' class = 'img_1' >";
        }
        if($temp_txt < $temp_max && $door_txt == 1){
            echo "<img src='z_svetlo/1_1_0.png' class = 'img_1' >";
        }
    }
    elseif ($sound_txt < $sound_o) {
        if($temp_txt > $temp_max && $door_txt == 1){
            echo "<img src='o_svetlo/1_1_1.png' class = 'img_1' >";
        }
        if($temp_txt > $temp_max && $door_txt == 0){
            echo "<img src='o_svetlo/1_0_1.png' class = 'img_1' >";
        }
        if($temp_txt < $temp_max && $door_txt == 0){
            echo "<img src='o_svetlo/1_0_0.png' class = 'img_1' >";
        }
        if($temp_txt < $temp_max && $door_txt == 1){
            echo "<img src='o_svetlo/1_1_0.png' class = 'img_1' >";
        }
    }
    else{
        if($temp_txt > $temp_max && $door_txt == 1){
            echo "<img src='č_svetlo/1_1_1.png' class = 'img_1' >";
        }
        if($temp_txt > $temp_max && $door_txt == 0){
            echo "<img src='č_svetlo/1_0_1.png' class = 'img_1' >";
        }
        if($temp_txt < $temp_max && $door_txt == 0){
            echo "<img src='č_svetlo/1_0_0.png' class = 'img_1' >";
        }
        if($temp_txt < $temp_max && $door_txt == 1){
            echo "<img src='č_svetlo/1_1_0.png' class = 'img_1' >";
        }
    }  
    fclose($file2);   
?>

<div class="values">
<?php
    $sound_low = "Low";
    $sound_medium = "Medium";
    $sound_high = "High";

    $sound_out = "";
    // tabuľka pre hodnoty
    $file2 = fopen("sensors.txt","r") or die("Unable to open file!");
    $text2 = fread($file2,filesize("sensors.txt"));
    $text_line = explode("|",$text2);

    $date_txt = (string)$text_line[1];
    $sound_txt = (int)$text_line[2];
    $temp_txt = (int)$text_line[3];
    $door_txt = (int)$text_line[4];
    if($door_txt == 0){
        $door_stat = "closed";
    }
    else{
        $door_stat = "open";
    }

    if($sound_txt < $sound_z){
        $sound_out = $sound_low;    
    }
    elseif($sound_txt < $sound_o){
        $sound_out = $sound_medium;    
    }
    else{
        $sound_out = $sound_high;
    }


    echo("<meta http-equiv='refresh' content='1.5'>"); //refreshen sa stránka každých 1.ť sekundy
    echo "<br>";
    echo "  <p><b>Room noise level:</b> {$sound_out}</p>
            <p><b>Room temperature:</b> {$temp_txt}</p>
            <p><b>Room door:</b> {$door_stat}</p>
        "; 
    //echo "<h3> " . date('H:i:s') . "</h3>";
    echo "<h3>$date_txt</h3>";
        fclose($file2); 
?>

</div>
</body>
</html>