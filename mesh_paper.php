<?php

$servername = "morpheome-rds.ckwefs6v2vcx.us-east-1.rds.amazonaws.com";
$username = "morpheome";
$password = "petersonlab";
$db = "morpheomeDB";

$servername = "localhost";
$username = "root";
$password = "";
$db = "morpheome";




/*
[program:meshpaper]
command=php /home/ubuntu/php/mesh_paper.php
autostart=true
autorestart=true
user=ubuntu
redirect_stderr=true
stdout_logfile=/home/ubuntu/logs/mesh_paper_worker.log
*/
try {
    $conn = new PDO("mysql:host=$servername;dbname=".$db, $username, $password,

    array(
        PDO::ATTR_TIMEOUT => 10,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    )
);
    // set the PDO error mode to exception
    //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    //$conn->setAttribute(PDO::ATTR_TIMEOUT, 10);
  

 /* $DBH = new PDO(
    "mysql:host=$host;dbname=$dbname", 
    $username, 
    $password,
    array(
        PDO::ATTR_TIMEOUT => "Specify your time here (seconds)",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    )
);*/


    echo "Connected successfully"; 
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}


//ini_set('memory_limit', '134217728');
ini_set('memory_limit','1G');

echo 'foo';

$insert_db_table = 'mesh_paper';

$query = 'SELECT MeSH_terms.* FROM MeSH_terms left join '.$insert_db_table.' on MeSH_terms.id='.$insert_db_table.'.mesh_term_id where  '.$insert_db_table.'.mesh_term_id is null and LENGTH(MeSH_terms.term_name) >= 3 limit 100;';
 //aliases.type = "NCBI_official_symbol" and
/*$query = 'SELECT aliases.* FROM aliases left join '.$insert_db_table.' on aliases.id='.$insert_db_table.'.id where aliases.type = "NCBI_official_symbol" and '.$insert_db_table.'.alias_id is null';*/

$sth = $conn->prepare($query);
$sth->execute();
/* Fetch all of the values in form of a numeric array */
$result = $sth->fetchAll();

$time_start = microtime(true);


echo 'foo1';
//$disease = 'cancer';
foreach($result as $row){

echo 'foo2';
print_r($row);


    

    $query = trim($row['term_name']);

    if(stripos($query, 'gene')==false){
        $query = $query." gene";
    }
    /*if(strpos($query, ' ') !== false){
        // multiple words
    }
    else{
        // one word 
        $query = $query." gene";
    }*/

    $query = str_replace(" ", " +", $query);

    //$query = str_replace("+ +", "+", $query);
    //$arr_exclude = ['Acute', 'Injuries']; //['was',"impact",'trap','ighv(iii)6', "its", 'protease', 'polymerase'];

    //if(in_array(trim($row['term_name']), $arr_exclude)) continue;

    //ini_set('memory_limit', '-1');
    $query2 = 'SELECT pmid FROM publications WHERE match(abstract) against("+'.str_replace(["-", "@"], ["", ""],$query).'" IN BOOLEAN MODE);';
    //$result = $conn->query($query);

    $sth2 = $conn->prepare($query2);
    echo 'foo2a: '.$query2;
    $sth2->execute();
    echo 'foo2b';
    /* Fetch all of the values in form of a numeric array */
    $result2 = $sth2->fetchAll();
    echo 'foo2c';

    if(count($result2) > 0){

        echo 'foo3: '.$row['id'];

        foreach($result2 as $row2){
            $query3 = 'INSERT into '.$insert_db_table.' (mesh_term_id,pmid) values ("'.$row['id'].'", "'.$row2['pmid'].'")';
            //$result = $conn->query($query);

            $sth3 = $conn->prepare($query3);
            $sth3->execute();
            echo 'insert '.$insert_db_table.':'.$row['id']."\n";        
        }
    }
    /*else{
        //ACTG1P1

        echo 'foo4: '.$row['id'];
        $query3 = 'INSERT into aliases_orphans (id,id,citation_count) values ("'.$row['id'].'",'.$row['id'].', 0)';
        //$result = $conn->query($query);

        $sth3 = $conn->prepare($query3);
        $sth3->execute();     

        echo 'insert aliases_orphans:'.$row['id']."\n";     
    }*/


    echo 'foo5: '.$row['id'];

    $time_end = microtime(true);
    $time = $time_end - $time_start;

    echo "- in $time seconds\n";

    /* Fetch all of the values in form of a numeric array */
    //$result3 = $sth->fetchAll();
}


$conn = null;

 ?>