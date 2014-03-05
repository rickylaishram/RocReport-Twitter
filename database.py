#!/usr/bin/python
# -*- coding: utf-8 -*-

import sqlite3, time
import data

class Database():
	__conn = None
	__c = None

	def connect(self):
		self.__conn = sqlite3.connect(data.DB.name)
		self.__c = self.__conn.cursor()
		self.__create_table()

	# Create the table if it do not exist
	def __create_table(self):
		query = '''CREATE TABLE IF NOT EXISTS sinceid (date text, since integer)'''
		self.__c.execute(query)

	# Returns the most recent since id
	# Returns as int
	def get_since(self):
		query = '''SELECT IFNULL(MAX(since),0) FROM sinceid '''
		self.__c.execute(query)
		return self.__c.fetchone()[0]

	# Insert a since id
	def set_since(self, since):
		query = '''INSERT INTO sinceid VALUES (?, ?) '''
		self.__c.execute(query, (time.time(), int(since)))
		self.__conn.commit()

	def close(self):
		self.__conn.close()

