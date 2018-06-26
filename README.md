# Malath SMS API for PHP Composer

## Installation

```shell
composer require vzool/malath_sms
```

## Usage

### Add sender

```php
$DTT_SMS = new Vzool/Malath/Malath_SMS($UserName, $Password, 'UTF-8');
$Send = $DTT_SMS->AddSender($Name);
```

### Check UserName & Password

```php
$DTT_SMS    = new Vzool/Malath/Malath_SMS($UserName, $Password, 'UTF-8');
$CheckUser  = $DTT_SMS->CheckUserPassword();
```

### Send SMS

```php
$DTT_SMS    = new Vzool/Malath/Malath_SMS($UserName, $Password, 'UTF-8');
$Send = $DTT_SMS->Send_SMS($Mobiles, $Originator, $SMS_Msg);
```

## HTTP Request Documentation:

1. Description – Bulk SMS.
2. HTTP Connectivity.
3. History.
4. SMS Specifications.
5. HTTP URLs.

a. Check User Name and Password.

b. Sending Process - Single & Multiple Messages URL.

c. Balance query.

d. Send Scheduled Message.

e. Insert Main Group.

f. Insert Sub Group.

g. Add Contact to Group.

h. add sender name.

I. Review sender name.


# Bulk SMS

In an era of rapid technological advancements, mode of

communication is becoming lot more sophisticated. A new and

modern way of reaching your target market and communicating

them is through SMS (Short Messaging Service). Malath SMS

offers you this mode of communication through its bulk SMS

service.

Bulk SMS service reaches your target audience at a very

economical price, putting behind the conventional mode of

advertising that costs you in multiples compared to SMS service.

High Speed messaging in world, yet simple and reliable. We provide

you the fastest messaging solutions allowing the organizations to

communicate with their clients on urgent and personal basis.


# HTTP Connectivity

This document covers the HTTP method of connectivity. Additional

documents are available for other types of connectivity.

This is one of the simpler server-based forms of communication to

with our gateway. It can be used either in the form of a HTTP POST

or as an URL. We recommend POST for larger data transfer, due

to the size limitation of GET.

Communication to our API can be done via HTTP on port 80. All

calls to the API must be URL-encoded.

The beauty and add-on advantage of http connectivity is that you

can check or access the gateway even on web based applications.

The parameter names are case-sensitive, so it is important to read

entire document prior to seeking assistance from IBS.


History

This part of URL deals with the messages history and that

includes the messages delivered and outstanding. The beauty and

add-on advantage of http connectivity is that you can check or

access the gateway even on web based applications.

Login to [http://sms.malath.net.sa](http://sms.malath.net.sa) to history. You can check

whether messages are delivered or outstanding.

SMS Specifications

Unicode:

Single Message: A message in Unicode format can contain up to

70 characters.

Multiple Message: Once the message exceeds more than 70

characters it would be sent in multiples with 67 characters in each

message.

English:

Single Message: A message in English format can contain up to

160 characters.

Multiple Message: Once the message exceeds more than 160

characters it would be sent in multiples with 134 characters in

each message.


# HTTP URLS

## A- Check User Name and Password

You can check your user name and password from API.

```
URL for Check User Name and Password
http://sms.malath.net.sa/apis/users.aspx?code=1&username=xxxx&password=xxxx
```
Return Codes

### 3101

```
Success
```
### 3102

```
Wrong Password
```
### 3103

```
User Name Don’t Exist
```
### 3104

```
Account Inactive
```
### 3105

```
Missing Parameter
```

B- Sending Process:

Unicode:

```
URL for Single SMS service
http://sms.malath.net.sa/httpSmsProvider.aspx?username=xxxxxx&password=xxxxxx&mobile= 9665 x
xxxxx&unicode=U&message=062A062C
0020062C064806270644&sender=your sender name here
```
```
URL for Multiple SMS service
http://sms.malath.net.sa/httpSmsProvider.aspx?username=xxxxxx&password=xxxxxx&mobile=9665x
xxxxx,9665xxxxxx&unicode=U&message=062A062C
8064206390020062C064806270644&sender=your sender name here
```
```
In this URL for sending on multiple mobile you have to separate with comma(,)
```
Note: must be active your sender name before do any test.

English:

URL for Single SMS service
[http://sms.malath.net.sa/httpSmsProvider.aspx?username=xxxxxx&password=xxxxxx&mobile=9665xxxxxx&unicode=E&message=test from Malath sms&sender=your sender name here](http://sms.malath.net.sa/httpSmsProvider.aspx?username=xxxxxx&password=xxxxxx&mobile=9665xxxxxx&unicode=E&message=test from Malath sms&sender=your sender name here)

URL for Multiple SMS service
[http://sms.malath.net.sa/httpSmsProvider.aspx?username=xxxxxx&password=xxxxxx&mobile=9665xxxxxx,9665xxxxxx&unicode=E&message= test from Malath sms&sender=your sender name here](http://sms.malath.net.sa/httpSmsProvider.aspx?username=xxxxxx&password=xxxxxx&mobile=9665xxxxxx,9665xxxxxx&unicode=E&message= test from Malath sms&sender=your sender name here)

In this URL for sending on multiple mobile you have to separate with comma(,)

Note: must be active your sender name before do any test.


# HTTP Parameters

Username: This is your account username given by Malath SMS

Password: this is password of your account Mobile: This is

recipient mobile number. Format should be like 9665xxxxxx

Unicode: This is the code which represents type of message. The

values will be E for English, U for Unicode. Describe....

Message: Actual message. If this English it will be English. For

sending Unicode, you need to convert into hexacode.

Sender: This is sender address. This can be only in English and only

up to 11characters.

Return Codes

### 0

```
Success
```
### 101

```
Parameter are missing
```
### 104

```
either user name or password are missing or your Account is on hold
```
### 105

```
Credit are not available
```
### 106

```
wrong Unicode
```
### 107

```
blocked sender name
```
### 108

```
missing sender name
```

C- Balance query:

If you want to know your balance after sending SMS, you can follow

these steps:

1 - Replace the variables in this following link with the suitable

values, and then open it via the browser or any other programming

language.

URL for Balance Query service
[http://sms.malath.net.sa/api/getBalance.aspx?username=xxxxxx&password=xxxxxxxx](http://sms.malath.net.sa/api/getBalance.aspx?username=xxxxxx&password=xxxxxxxx)

Return Codes

Error~
There is a wrong content in the link

Error~
You have not a permission to or your account info is incorrect


D- Send Scheduled Message:

Send Scheduled Message the formate of date and time

date=25/12/2010 , time=18:00 , unicode = U for unicode arabic

and E for English

URL for Send Scheduled Message service in English
[http://sms.malath.net.sa/apis/users.aspx?code=8&username=xxxx&password=xxxx&](http://sms.malath.net.sa/apis/users.aspx?code=8&username=xxxx&password=xxxx&)
mobile=9665xxxxxx&sender=SMS&Date= 28 / 12 /2011&Time=13:00&message=Hi&u
nicode=E

URL for Send Scheduled Message service in ARABIC
[http://sms.malath.net.sa/apis/users.aspx?code=8&username=xxxxx&password=xxx&](http://sms.malath.net.sa/apis/users.aspx?code=8&username=xxxxx&password=xxx&)
mobile=9665xxxxxx&sender=SMS&Date= 28 / 12 /2011&Time=13:00&message=062A
062C063106280647002006450646002006450648064206390020062C064806270644&unicode=U

```
In this URL for sending on multiple mobile you have to separate with comma(,)
```
Note: must be active your sender name before do any test.

Return Codes

```
Success 3101
```
```
Parameter are missing 101
```
```
either user name or password are missing or your Account is on hold 104
```
```
Credit are not available 105
```
```
wrong Unicode 106
```
```
blocked sender name 107
```
```
missing sender name 108
```
```
Block Keyword 109
```

E- Insert Main Group:

Add Main Group in your Account throw API.

URL for Add Main Group
[http://sms.malath.net.sa/apis/users.aspx?code=4&username=xxxx&password=xxxx&](http://sms.malath.net.sa/apis/users.aspx?code=4&username=xxxx&password=xxxx&)
main=MainGroupname

Return Codes

```
3101
Success
```
### 3102

```
Wrong Password
```
### 3103

```
User Name Don’t Exist
```
### 3104

```
Account Inactive
```
### 3105

```
Missing Parameter
```
### 3911

```
Add Group Successive
```
```
Add Group Failed 3914
```

F- Insert Sub Group:

Add Sub Group in your Account throw API.

URL for Add Sub Group
[http://sms.malath.net.sa/apis/users.aspx?code=5&username=xxxx&password=xxxx&](http://sms.malath.net.sa/apis/users.aspx?code=5&username=xxxx&password=xxxx&)
main=MainGroupID&sub=SubGroupName

Return Codes

```
3101
Success
```
### 3102

```
Wrong Password
```
### 3103

```
User Name Don’t Exist
```
### 3104

```
Account Inactive
```
### 3105

```
Missing Parameter
```
### 3911

```
Add Group Successive
```
```
Add Group Failed 3914
```
```
Wrong Main Group 3913
```

G- Add Contact to Group:

You can add Contact to your group easily throw API.

URL for Add Contact to Group
[http://sms.malath.net.sa/apis/users.aspx?code=6&username=xxxx&password=xxxx&](http://sms.malath.net.sa/apis/users.aspx?code=6&username=xxxx&password=xxxx&)
name=Ahmad,Subhi,Khaled&number=9665555xxx,96659787xxx,9665554544xxx&s
ub=

Return Codes

### 3101

```
Success
```
### 3102

```
Wrong Password
```
### 3103

```
User Name Don’t Exist
```
### 3104

```
Account Inactive
```
### 3105

```
Missing Parameter
```
### 3333

```
Missing Contact Number Or Contact Name
```

H - Add Sender Name:

You can add Sender name to your account easily throw API.

```
URL for Check User Name and Password
http://sms.malath.net.sa/apis/users.aspx?code= 2 &username=xxx&password=xxxx&
newsender=xxxxxx
```
Return Codes

```
Success 3101
```
```
Wrong Password 3102
```
```
User Name Don’t Exist 3103
```
```
Account Inactive 3104
```
```
Missing Parameter 3105
```
```
Sender Name Violation Rule 443
```
```
Time Out Operation 3405
```
```
Sender Name Received 143
```
```
Sender Name exist 444
```

## I - Review Sender Names:

You can Review your Sender name easily throw API.

```
URL for Check User Name and Password
http://sms.malath.net.sa/apis/users.aspx?code= 9 &username=xxx&password=xxx
```
Return Codes

```
Review
```
Success (^) senders
Wrong Password 3102
User Name Don’t Exist 3103
Account Inactive 3104
Missing Parameter 3105
Time Out Operation 3405

# END


