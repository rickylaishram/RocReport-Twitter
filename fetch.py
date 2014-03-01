#!/usr/bin/python
# -*- coding: utf-8 -*-

from twython import Twython
import json, requests, time, urllib, chardet

CONSUMER_KEY = ""
CONSUMER_SCERET = ""
ACCESS_TOKEN = ""
ACCESS_TOKEN_SECRET = ""

API_ENDPOINT_Add = ""
EMAIL = ""
PASSWORD = ""

HASHTAG = "#rocreport"

def fetchTweetsN():

	f = open('/root/rocreport-twitter/sincefile.txt', 'r')

	since = f.read()
	since = int(since.strip())
	print since
	#print type(int(since))
	#since = 0
	twitter = Twython(CONSUMER_KEY, CONSUMER_SCERET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET)
	response = twitter.search(q=HASHTAG, result_type='recent', count='100', include_entities=1, since_id=since)

	tweets = []
	since_id = since
	f.close()

	for tweet in response['statuses']:
		if since_id < tweet["id"]:
			since_id = tweet["id"]
		
		print (tweet["id"] > int(since))
		#print tweet["text"]
		#print tweet['geo']
		if (tweet['geo'] is not None) and (tweet["id"] > int(since)):
			photo_url = "1"

			try:
				for item in tweet['entities']['media']:
					photo_url = item['media_url']
			except Exception, e:
				pass
			
			print photo_url
			data = urllib.urlencode({"rocrep_update_nat":"Twitter","rocrep_update_name":HASHTAG, "rocrep_update_more": tweet["text"], "rocrep_update_pic": photo_url, "rocrep_update_latlong": str(tweet["geo"]["coordinates"][0])+";"+str(tweet["geo"]["coordinates"][1]), "rocrep_update_location": "From Twitter", "ismobile": "yes", "emails": EMAIL, "passwords": PASSWORD})
			u = urllib.urlopen(API_ENDPOINT_Add, data)
			print u.read()
			print data

	f = open('/root/rocreport-twitter/sincefile.txt', 'w+b')
	print str(since_id)
	f.write(str(since_id))
	f.close()


def main():
	fetchTweetsN()

if __name__ == '__main__':
 	main()
