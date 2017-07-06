## Obtaining and analyzing ~27M PubMed citations

#### How to import data to MySQL and analyze it.

1. obtain TSV files from Max at UCSC. There is ~1200 files, which contain collections of distinct pubmed citations. See papers.txt (or articles_used.txt) for ~1200 file names used to make the MySQL data snapshot around 7/4/17. `max_download_papers.py` 

2. unzip the .articles files `gunzip *.articles.gz`

3. upload to mysql with `upload_mysql.php`

4. generate gene_disease pivot table `disease_gene_rank_v2.php`

5. generate gene_paper pivot table `disease_paper.php`

6. analyze data with MySQL. See this file for documentation on what each query does: `morpheome-db-queries.sql`

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