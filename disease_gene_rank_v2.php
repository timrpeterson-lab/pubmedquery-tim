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

$table = 'gene_disease';
$field = 'disease_type';

$diseases = ['cancer','infection','alzheimer','cardiovascular','diabetes','obesity','depression','inflammation','osteoporosis','hypertension','stroke'];

//ini_set('memory_limit', '134217728');
ini_set('memory_limit','1G');

    $query = 'SELECT aliases.* FROM aliases left join gene_disease on aliases.gene_id=gene_disease.gene_id where type = "NCBI_official_symbol" and LEN(aliases.name) > 3 and gene_disease.gene_id is null;'; //where gene_id between 1 and 5000
       //$result = $conn->query($query);
   
   $sth = $conn->prepare($query);
    $sth->execute();
    /* Fetch all of the values in form of a numeric array */
    $result = $sth->fetchAll();

$time_start = microtime(true);

// Sleep for a while
//usleep(100);



 $ids = [];
foreach($diseases as $disease){ 

    //$disease = 'cancer';
    foreach($result as $row){

        $ids[] = $row['name'];

        //ini_set('memory_limit', '-1');
        $query2 = 'SELECT count(*) as publication_count FROM publications WHERE match(abstract) against("+'.str_replace("-", "",$row['name']).' +'.$disease.'" IN BOOLEAN MODE);';
        //$result = $conn->query($query);

        $sth2 = $conn->prepare($query2);
        $sth2->execute();
        /* Fetch all of the values in form of a numeric array */
        $result2 = $sth2->fetchAll();

        $query3 = 'INSERT into gene_disease_copy (gene_id,alias_id,disease_type,publication_count) values ("'.$row['gene_id'].'",'.$row['id'].', "'.$disease.'", "'.$result2[0]['publication_count'].'")';
        //$result = $conn->query($query);

        $sth3 = $conn->prepare($query3);
        $sth3->execute();
        echo 'insert:'.$row['gene_id'].'",'.$row['id'].', "'.$disease.'", "'.$result2[0]['publication_count'];

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        echo "- in $time seconds\n";

        /* Fetch all of the values in form of a numeric array */
        //$result3 = $sth->fetchAll();
    }
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