import re
import os
import sys

os.chdir(sys.path[0])
filename = 'Evernote.enex'
f = open(filename, "r")
theLargeString = f.read()
f.close()

theLargeString = re.sub('<resource>.+?</resource>', "<resource></resource>", theLargeString, 0, re.DOTALL)
theLargeString = re.sub("<content>.+?</content>", '<content><![CDATA[<?xml version="1.0" encoding="utf-8"?><!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd"><en-note></en-note>]]></content>', theLargeString, 0, re.DOTALL)

f = open(filename, "w")
f.write(theLargeString)
f.close()
