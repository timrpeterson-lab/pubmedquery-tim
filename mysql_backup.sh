/usr/local/mysql/bin/mysqldump -P 3306 -h 127.0.01 -u root -p morpheome | gzip -c > morpheome-tim-db-`date +%Y%m%d`.sql.gz
aws s3 cp morpheome-tim-db-`date +%Y%m%d`.sql.gz s3://timrpeterson-lab/morpheome/
rm morpheome-tim-db-`date +%Y%m%d`.sql.gz