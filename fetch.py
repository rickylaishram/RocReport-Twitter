#!/usr/bin/python
# -*- coding: utf-8 -*-

from twitter import *
import json, requests, time, urllib, chardet, sqlite3, sys
import data, database

def get_since():
	d = database.Database()
	d.connect()
	since = d.get_since()
	d.close()
	return since

def set_since(since):
	d = database.Database()
	d.connect()
	d.set_since(since)
	d.close()

def fetch_tweets(location):

	auth = OAuth(
		consumer_key = data.TwitterData.api_key,
		consumer_secret = data.TwitterData.api_secret,
		token = data.TwitterData.access_token,
		token_secret = data.TwitterData.access_token_secret
	)

	t = Twitter(auth = auth)

	since = get_since()
	
	tweets = t.search.tweets(q=data.TwitterData.hashtag, result_type='recent', count='100', include_entities=1, since_id=since, geocode=data.Geo.location[location])

	print tweets['search_metadata']

	for tweet in tweets['statuses']:
		
		if since < tweet['id']:
			since =  tweet['id']

		if (tweet['geo'] is not None):
			photo_url = None

			try:
				for item in tweet['entities']['media']:
					photo_url = item['media_url']
			except Exception, e:
				pass

			try:
				latitude = tweet['geo']['coordinates'][0]
				longitude = tweet['geo']['coordinates'][1]
				message = tweet['text']
				name = '@'+tweet['user']['screen_name']

				#print latitude, longitude, message, name, photo_url
				#print tweet['id']

				update_server({'name': name, 'message': message, 'photo': photo_url, 'latitude': latitude, 'longitude': longitude})
			except Exception, e:
				pass
	set_since(since)

# Todo
def update_server(data):
	pass

def main():
	location = sys.argv[1]
	fetch_tweets(location)

if __name__ == '__main__':
	main()
