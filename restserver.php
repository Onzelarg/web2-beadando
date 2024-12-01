<?php

include(dirname(__FILE__)."/../dbconnect.php");


    $eredmeny = "";
    try {

        $mySql=connectDatabase();

        switch($_SERVER['REQUEST_METHOD']) {

            case "GET":
                $query = "SELECT * FROM pizza ORDER BY nev";
                $response = mysqli_query($mySql,$query);
    
                $eredmeny = "<table><tr><th> Nev </th><th> Kategoria </th><th> Vegeraianus ? </th></tr>";
    
                while($row = mysqli_fetch_row($response)){
                    $IsVegetarian = $row[2]==1 ? "Igen" : "Nem";
                    
                    $eredmeny.="<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$IsVegetarian."</td></tr>";  
                }
                mysqli_close($mySql);

            break;

            case "POST":

                $name= $_POST['n'];
                $kategoria= $_POST['k'];
                $vega= $_POST['v'];

                if($vega!=0 || $vega!=1){
                    $vega=0;
                }

                $query = "INSERT INTO pizza VALUES ('".$name."','".$kategoria."','".$vega."')";
                print "Query: ".$query;
                if(mysqli_query($mySql,$query)){
                    $eredmeny= "\n$1$ Successfully added new entry!";
                }else{
                    $eredmeny= "\n$0$ Could not insert record: ". mysqli_error($mySql);  
                }

                mysqli_close($mySql);

            break;

            case "PUT":
                $data = array();
                $incoming = file_get_contents("php://input");
                parse_str($incoming, $data);

                $query="UPDATE `game` SET ";
                $query.="`nev`='".$data["n"]."',";
                if($data["k"]!=""){ $query.="`kategorianev`='".$data["k"]."',";}
                if($data["v"]!=""){ $query.="`vegetarianus`='".$data["k"]."',";}
                $query.=" WHERE nev=".$data["n"];
                print $query;
                if(mysqli_query($mySql,$query)){
                    $eredmeny= "Sikeresen módositva.";
                }else{
                    $eredmeny= "Nem sikerült modositani. Hiba oka: ". mysqli_error($conn);  
                }


            break;

            case "DELETE":

                $data = array();
                $incoming = file_get_contents("php://input");
                parse_str($incoming, $data);

                $query = "DELETE from pizza where nev='".$data["nev"]."';";
                print $query;
                if(mysqli_query($mySql,$query)){
                    $eredmeny= "Sikeresen törölve!";
                }else{
                    $eredmeny= "Nem sikerült. Hiba oka: ". mysqli_error($conn);  
                }

            break;

        }
    }
    catch (PDOException $e) {
        $eredmeny = $e->getMessage();
    }
    print $eredmeny;
?>