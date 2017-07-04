<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "morpheome";

try {
    $conn = new PDO("mysql:host=$servername;dbname=".$db, $username, $password,
    [PDO::MYSQL_ATTR_LOCAL_INFILE => true]);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    
    echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

//ini_set('memory_limit', '-1');
    $query = 'SELECT pmid FROM publications WHERE match(abstract) against("+diabetes IN BOOLEAN MODE");';
       //$result = $conn->query($query);
   
   $sth = $conn->prepare($query);
    $sth->execute();
    /* Fetch all of the values in form of a numeric array */
    $result = $sth->fetchAll();


print_r($result);


 $ids = [];
foreach($result as $row){
	$ids[] = $row['pmid'];
}

//unset($result);

/*	$ids_str = implode(',', $ids);
	$query2 = "SELECT PMID, Mentions, NCBI_Gene, count(*) as count from `gene2pubtator` where PMID in ('".$ids_str."') group by NCBI_Gene order by count desc";
   $sth2 = $conn->prepare($query2);
    $sth2->execute();
    /* Fetch all of the values in form of a numeric array */
/*    $result2 = $sth2->fetchAll();

print_r($result2);

/*select *, count(*) as count from 
(select * from (SELECT pmid as pid, abstract, title FROM publications WHERE match(abstract) against('+mTOR' IN BOOLEAN MODE)) j
left join gene2pubtator
on j.pid=gene2pubtator.PMID) k
 group by k.NCBI_Gene order by count desc*/

$conn = null;

 ?>