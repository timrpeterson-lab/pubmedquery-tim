from urllib2 import urlopen
import re

urlpath =urlopen('http://hgwdev.soe.ucsc.edu/~max/trpeterson/medline/')
string = urlpath.read().decode('utf-8')

pattern = re.compile('(10000)|(0*\d{1,5}_0*\d{1,5}).articles.gz') #the pattern actually creates duplicates in the list

filelist = pattern.findall(string)
#print(filelist)

for filename in filelist:
	#print(filename)
				
	filename=filename[1]

	#print(filename)
	filename += '.articles.gz'

	print(filename)
	remotefile = urlopen('http://hgwdev.soe.ucsc.edu/~max/trpeterson/medline/' + filename)
	localfile = open(filename,'wb')
	localfile.write(remotefile.read())
	localfile.close()
	remotefile.close()


'''import urllib

testfile = urllib.URLopener()

for number in range(0, 1067, 1):
	#print(number)

	number = '{:05}'.format(number)

	filename = "0_" + number+".articles.gz"'''

	#print(filename)

	#testfile.retrieve("http://hgwdev.soe.ucsc.edu/~max/trpeterson/medline/" + filename, filename)


'''   0_00001.articles.gz

   0_00011.articles.gz

   25_00331.articles.gz'''



'''import urllib2
import re

url = "http://hgwdev.soe.ucsc.edu/~max/trpeterson/medline/"
#connect to a URL
website = urllib2.urlopen(url)

#read html code
html = website.read()

#print(html)
#use re.findall to get all the links
links = re.findall('articles.gz', html)

print links'''


