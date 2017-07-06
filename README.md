## Obtaining and analyzing ~27M PubMed citations

#### How to import data to MySQL and analyze it.

1. obtain TSV files from Max at UCSC. There is ~1200 files, which contain collections of distinct pubmed citations. See papers.txt (or articles_used.txt) for ~1200 file names used to make the MySQL data snapshot around 7/4/17. `max_download_papers.py` 

2. unzip the .articles files `gunzip *.articles.gz`

3. upload to mysql with `upload_mysql.php`

4. generate gene_disease pivot table `disease_gene_rank_v2.php`

5. generate gene_paper pivot table `disease_paper.php`