# Technologies

1. PHP 8

2. Maria DB 10

3. Composer

4. Apache 2


# Installation 

1. Upload all files in a directory in the server

2. run "composer install"

3. Import sql file "nordsec.sql" in the Maria DB

4. Provide Database connection in the file ".env"


# API Doc

 
## User Registration

Url : /register
Method : POST
Data Format : x-www-form-urlencoded
Data:

string firstName (required)
string lastName (required)
string email (required, unique)
string uuid (required, unique)
string pass (required)
string passWordConfirm (required)
string mobileNumber
string address
string city
string zipCode
string country

## User Update

Url : /update
Method : POST
Data Format : x-www-form-urlencoded
Data:

string firstName (required)
string lastName (required)
string email (required, unique)
string uuid (required, unique)
string pass (required)
string passWordConfirm (required)
string apiKey (required)
string mobileNumber
string address
string city
string zipCode
string country

## User Login

Url : /login
Method : POST
Data Format : x-www-form-urlencoded
Data:

string email (required)
string pass (required)

## User Delete

Url : /delete
Method : DELETE
Data Format : params
Data:

string apiKey (required)

## Scooter Registration

Url : /sregister
Method : POST
Data Format : x-www-form-urlencoded
Data:

string firstName (required)
string lastName (required)
string email (required)
string uuid (required)
string pass (required)
string passWordConfirm (required)
string mobileNumber 
string regNumber
string city
string country

## Scooter Update

Url : /supdate
Method : POST
Data Format : x-www-form-urlencoded
Data:

string firstName (required)
string lastName (required)
string email (required)
string uuid (required)
string pass (required)
string passWordConfirm (required)
string apiKey (required)
string mobileNumber 
string regNumber
string city
string country

## Scooter Login

Url : /slogin
Method : POST
Data Format : x-www-form-urlencoded
Data:

string email (required)
string pass (required)

## Scooter Delete

Url : /sdelete
Method : DELETE
Data Format : params
Data:

string apiKey (required)

## Scooter Status (Get the status, available=1, not available=0 or occupied=3, of scooters)

Url : /status
Method : GET
Data Format : params
Data:

string apiKey (required)

## Scooter Logout (scooter is not available for trip when it is logged out)

Url : /slogout
Method : GET
Data Format : params
Data:

string apiKey (required)

## Trip Create by User

Url : /trip/create
Method : POST
Data Format : x-www-form-urlencoded
Data:

string scooterId (required)
string userId (required)
string payment (required)
string apiKey (required)
string startLat (required), Latitude 
string startLon (required), Longitude
string endLat (required) , Latitude 
string endLon (required) , Longitude