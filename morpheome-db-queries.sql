

#generate gene+cancer vs. gene+other disease publication counts
select sum(a.publication_count) as cancer, b.other from gene_disease_copy a
join (select gene_id, sum(publication_count) as other from gene_disease_copy where disease_type!='cancer' and publication_count > 0  group by gene_id having sum(publication_count) <100000) b
on a.gene_id=b.gene_id
where a.publication_count > 0 and disease_type='cancer' group by a.gene_id  having sum(publication_count) < 50000 


select a.gene_id, sum(a.publication_count) as cancer from gene_disease_copy a
where a.publication_count > 0 having sum(a.publication_count) <50000) group by a.gene_id

select gene_id, sum(publication_count) as other from gene_disease_copy where disease_type!='cancer' and publication_count > 0 group by gene_id

SELECT count(*) as publication_count FROM publications WHERE match(abstract) against("+ACTG1P1" IN BOOLEAN MODE);

SELECT aliases.* FROM aliases left join gene_paper_copy on aliases.gene_id=gene_paper_copy.gene_id where type = "NCBI_official_symbol" and gene_paper_copy.alias_id is null

SELECT aliases.* FROM aliases left join gene_paper_copy on aliases.gene_id=gene_paper_copy.gene_id left join aliases_orphans on aliases.id=aliases_orphans.id where aliases.type = "NCBI_official_symbol" and gene_paper_copy.alias_id is null and aliases_orphans.id is null;

SELECT pmid FROM publications WHERE match(abstract) against("+WAS" IN BOOLEAN MODE);


select gene_paper_copy.gene_id, aliases.name, count(gene_paper_copy.gene_id) as count from gene_paper_copy
right join (

SELECT pmid FROM publications WHERE match(abstract) against("+osteoporosis" IN BOOLEAN MODE)) p

on gene_paper_copy.pmid=p.PMID 
left join `aliases`
on gene_paper_copy.gene_id=aliases.gene_id
group by gene_paper_copy.gene_id order by count desc;

# this is perhaps the most useful query for MORPHEOME to find top-cited genes. It returns a ranked list of all the genes that co-occur with a given search term, in this example "osteoporosis".
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

#this query, which is similar to the one above doesn't work when you do the JOIN with the gene2pubtator table. Perhaps gene2pubtator table needs an index?
select * from aliases 
join (select gene2pubtator.NCBI_Gene, count(gene2pubtator.NCBI_Gene) as count from gene2pubtator
join (

SELECT * FROM publications WHERE match(abstract) against("+osteoporosis" IN BOOLEAN MODE)) p

on gene2pubtator.PMID=p.PMID 

group by gene2pubtator.NCBI_Gene) m
on aliases.gene_id=m.NCBI_Gene
where type = "NCBI_official_symbol"
group by aliases.gene_id 
 order by m.count desc;

select count(*) from gene_paper_copy
where gene_id=3845;

# rank all genes by # of citations they are referenced (this is irrespective of disease or any other 2nd criteria)
select * from aliases 
join (select *, count(pmid) count from gene_paper_copy
group by gene_id) c
on aliases.gene_id=c.gene_id group by aliases.gene_id
order by count desc;