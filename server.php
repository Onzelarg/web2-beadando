<?php
    include(dirname(__FILE__)."/../dbconnect.php");

    class service {



        public function getPage($page){
            print $page;
            if($page=="pizza"){ return "<h1>Hello world</h1>"; }
            //return file_get_contents($page.".html");
        }

        public function getPages(){
            $mySql = connectDatabase();
            $query = "SELECT * FROM menuk";

            $response = mysqli_query($mySql,$query);

            $result = [];

            while($row = mysqli_fetch_row($response)){
                
                $result[]= [$row[1],$row[2]];
            }

            mysqli_close($mySql);
            print json_encode($result);
            return $result;

        }

        public function getUser($username,$password){ 
            $success = 1;

            if(strlen($username)<3 || strlen($password)<3){
                $success = 0;
            }
            if($success){

                $mySql = connectDatabase();

                $query = "SELECT jelszo FROM felhasznalok WHERE felhasznalonev='".$username."';";
                
                $response = mysqli_query($mySql,$query);
                $result = mysqli_fetch_row($response);

                if(strlen($result[0])<1){
                    $success = 0;
                }

                if($password!=$result[0] && $success){
                    $success = 0;
                }
            }

            return $success; 
        }

        public function getRole($username){ 

            $mySql = connectDatabase();

            $query = "SELECT szerepkor FROM felhasznalok WHERE felhasznalonev='".$username."';";

            $response = mysqli_query($mySql,$query);
            $result = mysqli_fetch_row($response);

            return $result[0];
   
        }

        public function addUser($user,$password,$role){
            
            $mySql = connectDatabase();
            $success= true;

            if(strlen($user)>2 && strlen($password)>2){

            $query = "INSERT INTO felhasznalok VALUES ('".$user."','".$password."','".$role."')";

            print "Query: ".$query;
            if(mysqli_query($mySql,$query)){
                print "\n$1$ Successfully added new entry!";
            }else{
                print "\n$0$ Could not insert record: ". mysqli_error($mySql);  
            }

            }else{
                $success= false;
            }
            return $success;
            mysqli_close($mySql);
        }

        

        public function listAllCategory(){ 
            $mySql = connectDatabase();
            $query = "SELECT * FROM kategoria ORDER BY nev";
            $response = mysqli_query($mySql,$query);

            $result = "<table><tr><th> Nev </th><th> Kategoria </th></tr>";

            
            while($row = mysqli_fetch_row($response)){
                
                $result.="<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
                
            }

            mysqli_close($mySql);
            print $result;
            return $result;
            
        }

        public function listAllPizza(){ 
            $mySql = connectDatabase();
            $query = "SELECT * FROM pizza ORDER BY nev";
            $response = mysqli_query($mySql,$query);

            $result = "<table><tr><th> Nev </th><th> Kategoria </th><th> Vegeraianus ? </th></tr>";

            
            while($row = mysqli_fetch_row($response)){
                $IsVegetarian = $row[2]==1 ? "Igen" : "Nem";
                
                $result.="<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$IsVegetarian."</td></tr>";
                
            }

            mysqli_close($mySql);
            print $result;
            return $result;
            
        }

        public function listAllOrder(){ 
            $mySql = connectDatabase();
            $query = "SELECT * FROM rendeles ORDER BY az";
            $response = mysqli_query($mySql,$query);

            $result = "<table><tr><th> Nev </th><th> Darab </th><th> Felvétel </th> <th>Kiszállitás</th></tr>";

            
            while($row = mysqli_fetch_row($response)){
                
                $result.="<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$IsVegetarian."</td><td>".$row[2]."</td></tr>";
                
            }

            mysqli_close($mySql);
            print $result;
            return $result;
            
        }




        public function pdfPizza($kat){
            $mySql = connectDatabase();

            $query = "SELECT * FROM pizza WHERE kategorianev='".$kat."';";
            
            $response = mysqli_query($mySql,$query);


            while($row = mysqli_fetch_row($response)){
                $IsVegetarian = $row[2]==1 ? "Igen" : "Nem";
                $result.=$row[0]."    ".$row[1]."    ".$IsVegetarian."\n";
            }// [    ]
            
            return $result;
        }

        public function pdfVega(){
            $mySql = connectDatabase();

            $query = "SELECT * FROM pizza WHERE vegetarianus='1';";
            
            $response = mysqli_query($mySql,$query);


            while($row = mysqli_fetch_row($response)){
                $result.=$row[0]."    ".$row[1]."    Igen\n";
            }// [    ]
            
            return $result;
        }

        public function pdfDarab($darab){
            $mySql = connectDatabase();

            $query = "SELECT * FROM rendeles WHERE darab='".$darab."';";
            
            $response = mysqli_query($mySql,$query);

            while($row = mysqli_fetch_row($response)){
                
                $result.=$row[1]."    ".$row[2]."    ".$row[3]."    ".$row[4]."    ".$row[5];
                
            }
            
            return $result;
        }

    }
    $options = array(
        "uri" => "/https://3dshell.hu/soap/web2/server.php"
    );



    $server = new SoapServer(null, $options);
    $server->setClass('service');
    $server->handle();

    $t = new service();
    $t->getRole("aaaaaa");

?> 










<?php
    // SoapServer: The SoapServer class provides a server for the SOAP protocols.
    // It can be used with or without a WSDL service description.
    // http://php.net/manual/en/class.soapserver.php
    // null: nem használunk wsdl fájlt
?>