MailerSend (MiniSend)

* [Requirements](#step1)
* [Installation](#step2)
* [Test](#step3)
* [Run](#step4)

<a name="step1"></a>
### Requirements
* PHP 8.1
* Node ^16.0
* Yarn

<a name="step2"></a>
### Installation
* cp .env.example .env
* configure .env
* composer install
* yarn
* php artisan migrate
* php artisan storage:link
* php artisan db:seed


<a name="step4"></a>
### Test
* php artisan test

<a name="step4"></a>
### Run
* php artisan serve
* yarn build


