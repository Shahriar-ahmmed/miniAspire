
## About MiniAspire

 simple API that allows to handle user loans. Necessary entities : users, loans, and repayments.

creating a new client user with different attributes('name','email', 'phone','address'),

creating a new account with different attributes('user_id', 'amount',  'type','loan_status'), 

creating a new loan for a user with different attributes ('account_id','type','repayments_frequency', 'status', 
'duration', 'interest_rate', 'amount', 'paid_amount', 'balance_amount', 'number_of_instalment', 
'instalment_amount', 'arrangement_fee','penalty_fee'), 
                                                                  
and allowing a user to make repayments for the loan with different attributes ('loan_id', 'amount', 'status',
'penalty_fee', 'payment_date')

## Simple Instruction

PHP require => 7.1.3 

Keep miniAspire in xampp > htdocs or wamp > www .

Use Postman for API Checking. Create database then set name in env file. 

Under  project folder run cmd:  

php artisan migrate , php artisan db:seed and php artisan passport:client –
personal 
 
For api authentication token database has a user= admin@example.com and password=123456 

Login in miniAspire/api/login using post method and copy token and paste it for authorization in request header.  

Check all CRUD and show  of  client_users, accounts, loans, repayments Please see routes api.php for more.