<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "morpheome";

try {
    $conn = new PDO("mysql:host=$servername;dbname=".$db, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  
    echo "Connected successfully"; 
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}


//ini_set('memory_limit', '134217728');
ini_set('memory_limit','1G');

echo 'foo';

$insert_db_table = 'gene_paper_copy';
$query = 'SELECT aliases.* FROM aliases left join '.$insert_db_table.' on aliases.gene_id='.$insert_db_table.'.gene_id left join aliases_orphans on aliases.id=aliases_orphans.id where aliases.type = "NCBI_official_symbol" and '.$insert_db_table.'.alias_id is null and aliases_orphans.id is null and LENGTH(aliases.name) > 3';
 
/*$query = 'SELECT aliases.* FROM aliases left join '.$insert_db_table.' on aliases.gene_id='.$insert_db_table.'.gene_id where aliases.type = "NCBI_official_symbol" and '.$insert_db_table.'.alias_id is null';*/

$sth = $conn->prepare($query);
$sth->execute();
/* Fetch all of the values in form of a numeric array */
$result = $sth->fetchAll();

$time_start = microtime(true);


echo 'foo1';
//$disease = 'cancer';
foreach($result as $row){

echo 'foo2';

    if($row['name'] == "WAS" || $row['name'] == "IMPACT" || $row['name'] == 'TRAP') continue;

    //ini_set('memory_limit', '-1');
    $query2 = 'SELECT pmid FROM publications WHERE match(abstract) against("+'.str_replace(["-", "@"], ["", ""],$row['name']).'" IN BOOLEAN MODE);';
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
            $query3 = 'INSERT into '.$insert_db_table.' (gene_id,alias_id,pmid) values ("'.$row['gene_id'].'",'.$row['id'].', "'.$row2['pmid'].'")';
            //$result = $conn->query($query);

            $sth3 = $conn->prepare($query3);
            $sth3->execute();
            echo 'insert '.$insert_db_table.':'.$row['id']."\n";        
        }
    }
    else{
        //ACTG1P1

        echo 'foo4: '.$row['id'];
        $query3 = 'INSERT into aliases_orphans (gene_id,id,citation_count) values ("'.$row['gene_id'].'",'.$row['id'].', 0)';
        //$result = $conn->query($query);

        $sth3 = $conn->prepare($query3);
        $sth3->execute();     

        echo 'insert aliases_orphans:'.$row['id']."\n";     
    }


    echo 'foo5: '.$row['id'];

    $time_end = microtime(true);
    $time = $time_end - $time_start;

    echo "- in $time seconds\n";

    /* Fetch all of the values in form of a numeric array */
    //$result3 = $sth->fetchAll();
}


$conn = null;

 ?>