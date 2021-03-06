gj-redirect
===========

This plugin was built to alleviate doing heavy redirects with .htaccess. It allows the end user to upload a CSV (formatted properly) into the table. If a user types in a non-existant page, before WordPress routing loads the 404 template it will check the database for the path. If the path is matched it will redirect accordingly to the defined location via a 301 or 302.

## paths

This supports both absolute and relative paths in the table. It is suggested to use relative paths (/path/to/page/ instead of http://mysite.com/path/to/page). Currently the final output tests for the existance of http://, if it does not exist it appends the hostname. If it does then it suspects the url to be complete.

WordPress requires absolute paths for wp_redirect. This may have some bugs to work out in the future.

## database columns

### url

`url` = The URL to match

### redirect

`redirect` = The URL to redirect to

### status

`301` = Permanent Redirect  
`302` = Temporary Redirect  

### scope

Scope defines how we are matching the URL. Exact is the most similar to a standard:  

`Redirect 301 /url-a/ /url-b/`

`exact` = match url exactly  
`ignorequery` = match url exactly, ignore any query string  
`any` = match anywhere in url  

* `any` is current disabled 

## csv upload

Example CSV document:

url | redirect | status | scope
--- | -------- | ------ | -----
/apples/ | /bananas/ | 301 | exact
/turtles/?ref=water | /beach/ | 302 | ignorequery
/turtles/?ref=water | /beach/ | 302 | any

## 404 logging

There is a function in settings to log 404s to the database. USE WITH CAUTION and check the results often. Make use of the `ignorequery` option as a default to stop from logging additional 404s for the same URL.

## todo

[See Open Issues](https://github.com/GunnJerkens/gj-redirect/issues?page=1&state=open)

## license

MIT
