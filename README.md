## Obtaining and analyzing ~27M PubMed citations

#### How to import data to MySQL and analyze it.

1. obtain TSV files from Max at UCSC. There is ~1200 files, which contain collections of distinct pubmed citations. See papers.txt (or articles_used.txt) for ~1200 file names used to make the MySQL data snapshot around 7/4/17. `max_download_papers.py` 

2. unzip the .articles files `gunzip *.articles.gz`

3. upload to mysql with `upload_mysql.php`

4. generate gene_disease pivot table `disease_gene_rank_v2.php`

5. generate gene_paper pivot table `disease_paper.php`

6. analyze data with MySQL. The main issue with the data is that english words don't get recognized well. There are genes with official NCBI symbols like "MICE", "SET", "MET", and "COPD", that add noise to the returned results. Other genes like p53 have official symbols "TP53" that aren't as commonly used. The solution is manual curation. See this file for documentation on what each query does: `morpheome-db-queries.sql`

7. Perhaps the most useful query for MORPHEOME for top-cited gene ranking is described below. It returns a ranked list of all the genes that co-occur with a given search term, in this example "osteoporosis". It is slow (can be 30s), so it needs optimization if it will be used on a website. Perhaps, we need a index on some of the JOINed tables?

```
select * from aliases 
join (select gene_paper_copy.gene_id, count(gene_paper_copy.gene_id) as count from gene_paper_copy
join (
	SELECT * FROM publications WHERE match(abstract) against("+osteoporosis" IN BOOLEAN MODE)) p
	on gene_paper_copy.pmid=p.PMID 
	group by gene_paper_copy.gene_id) m
	on aliases.gene_id=m.gene_id
	where type = "NCBI_official_symbol"
	group by aliases.gene_id 
	order by m.count desc;
 ```

 8. Download a list of common queries from MeSH and put in csv using `mesh_xml2csv.php` (for easy import to MySQL) so we can pre-fetch results of top-cited-orphan gene pairs. Otherwise if each user had to run their query through all 28M papers and the rest of the pipeline, it would take forever. 

 9. Created mesh_paper table using `mesh_paper.php`.  Run php scripts as daemon (i.e., in the background) using this [https://dor.ky/run-php-script-as-daemon-using-supervisord/](https://dor.ky/run-php-script-as-daemon-using-supervisord/)

