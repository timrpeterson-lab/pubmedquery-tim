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




$files = glob('papers/*.articles');
$counter = 0;
foreach($files as $file){
	$counter++;
	if($counter < 545) continue;

	echo $file.' begin';
    $conn->query("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE publications ignore 1 lines (articleId,externalId,source,publisher,origFile,journal,printIssn,eIssn,journalUniqueId,year,articleType,articleSection,authors,authorEmails,authorAffiliations,keywords,title,abstract,vol,issue,page,pmid,pmcId,pii,doi,fulltextUrl,time,offset,size) ");
    echo $file.' finish';
}

$conn = null;

?>